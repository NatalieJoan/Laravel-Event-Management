<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
    }

    public function index(Event $event)
    {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Event $event, Attendee $attendee)
    {
        $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response(status: 204);
    }
}
