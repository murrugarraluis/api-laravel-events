<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

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

    public function store(EventCreateRequest $request): EventResource
    {
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
        return (new EventResource($event))->additional([
            'message' => 'Event created.'
        ]);
    }

    public function update(EventUpdateRequest $request, Event $event): EventResource
    {
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
        return (new EventResource($event))->additional([
            'message' => 'Event updated.'
        ]);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted.']);
    }
}
