<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventIndexRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EventController extends Controller
{
    const per_page = 12;
    const sort = 'desc';

    public function index(EventIndexRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->query('per_page', self::per_page);
        $sort = $request->query('sort', self::sort);
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $search = $request->query('search');
        $category = $request->query('category');
        $city = $request->query('city');

        // Start Query
        $query = Event::query();

        // Apply filter
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->whereRaw('LOWER(name) LIKE ?', [strtolower($category)]);
            });
        }
        if ($city) {
            $query->whereHas('city', function ($q) use ($city) {
                $q->whereRaw('LOWER(name) LIKE ?', [strtolower($city)]);
            });
        }
        if ($start_date || $end_date) {
            $query->when($start_date, function ($query) use ($start_date) {
                return $query->where('date', '>=', $start_date);
            })
                ->when($end_date, function ($query) use ($end_date) {
                    return $query->where('date', '<=', $end_date);
                });
        }
        // Search by Event Name
        if ($search) {
            $searchTerm = strtolower($search); // Convertir el término de búsqueda a minúsculas
            $query->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
            });
        }
        $query->orderByRaw("date $sort, time $sort");
        $events = $query->paginate($per_page);
        $events->appends([
            'per_page' => ($per_page !== self::per_page) ? $per_page : null,
            'sort' => ($sort !== self::sort) ? $sort : null,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'search' => $search,
            'category' => $category,
            'city' => $city,
        ]);
        return EventResource::collection($events);
    }

    public function show($id): EventResource
    {
        $event = Event::where('id',$id)
            ->orWhere('slug',$id)
            ->firstOrFail();
        return new EventResource($event);
    }

    public function store(EventCreateRequest $request): EventResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            $event = Event::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'content' => $request->input('content'),
                'description' => $request->input('description'),
                'poster' => $request->input('poster'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'category_id' => $request->input('category.id'),
                'city_id' => $request->input('city.id')
            ]);
            DB::commit();
            return (new EventResource($event))->additional([
                'message' => 'Event created.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if ($e->getCode() === "23000") {
                return response()->json(['message' => 'Slug duplicated.'], 400);
            }
            throw new BadRequestException($e->getMessage());
        }
    }

    public function update(EventUpdateRequest $request, Event $event): EventResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            $event->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'content' => $request->input('content'),
                'description' => $request->input('description'),
                'poster' => $request->input('poster'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'category_id' => $request->input('category.id'),
                'city_id' => $request->input('city.id')
            ]);
            $event->save();
            DB::commit();
            return (new EventResource($event))->additional([
                'message' => 'Event updated.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if ($e->getCode() === "23000") {
                return response()->json(['message' => 'Slug duplicated.'], 400);
            }
            throw new BadRequestException($e->getMessage());
        }
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted.']);
    }
}
