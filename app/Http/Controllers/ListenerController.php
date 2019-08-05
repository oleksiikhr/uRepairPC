<?php

namespace App\Http\Controllers;

use App\Events\JoinRooms;
use Illuminate\Http\Request;
use App\Events\Roles\EGlobal as RolesEvent;
use App\Events\Users\EGlobal as UsersEvent;
use App\Events\Requests\EGlobal as RequestsEvent;
use App\Events\Equipments\EGlobal as EquipmentsEvent;
use App\Events\RequestTypes\EGlobal as RequestTypesEvent;
use App\Events\EquipmentTypes\EGlobal as EquipmentTypesEvent;
use App\Events\EquipmentModels\EGlobal as EquipmentModelsEvent;
use App\Events\RequestStatuses\EGlobal as RequestStatusesEvent;
use App\Events\RequestPriorities\EGlobal as RequestPrioritiesEvent;
use App\Events\EquipmentManufacturers\EGlobal as EquipmentManufacturersEvent;

class ListenerController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            //
        ];
    }

    /**
     * Refresh all rooms for socketId and listen profile events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync()
    {
        // Listen events profile
        $user = auth()->user();
        $rooms = ["users.{$user->id}", "users.{$user->id}.roles"];

        // Listen events from other section (if can)
        $rooms = array_merge($rooms, UsersEvent::getRooms());
        $rooms = array_merge($rooms, RolesEvent::getRooms());
        $rooms = array_merge($rooms, EquipmentsEvent::getRooms());
        $rooms = array_merge($rooms, EquipmentTypesEvent::getRooms());
        $rooms = array_merge($rooms, EquipmentModelsEvent::getRooms());
        $rooms = array_merge($rooms, EquipmentManufacturersEvent::getRooms());
        $rooms = array_merge($rooms, RequestsEvent::getRooms());
        $rooms = array_merge($rooms, RequestTypesEvent::getRooms());
        $rooms = array_merge($rooms, RequestStatusesEvent::getRooms());
        $rooms = array_merge($rooms, RequestPrioritiesEvent::getRooms());

        // Send all rooms to listen
        event(new JoinRooms($rooms, true));

        return response()->json([
            'rooms' => $rooms,
        ]);
    }
}
