<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Event::all();
        return EventResource::collection($categories);
    }

    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    public function store(EventCreateRequest $request): EventResource | JsonResponse
    {
        DB::beginTransaction();
        try{
            $event = Event::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'content' => $request->input('content'),
                'description' => $request->input('description'),
                'poster' => $request->input('poster'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'category_id' => $request->input('category.id')
            ]);
            DB::commit();
            return (new EventResource($event))->additional([
                'message' => 'Event created.'
            ]);
        }catch (\Exception $e){
            DB::rollback();
            if($e->getCode() === "23000"){
                return response()->json(['message' => 'Slug duplicated.'],400);
            }
            throw new BadRequestException($e->getMessage());
        }
    }

    public function update(EventUpdateRequest $request, Event $event): EventResource | JsonResponse
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
                'category_id' => $request->input('category.id')
            ]);
            $event->save();
            DB::commit();
            return (new EventResource($event))->additional([
                'message' => 'Event updated.'
            ]);
        }catch (\Exception $e){
            DB::rollback();
            if($e->getCode() === "23000"){
                return response()->json(['message' => 'Slug duplicated.'],400);
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
