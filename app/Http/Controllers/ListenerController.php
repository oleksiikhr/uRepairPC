<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Realtime\JoinRooms;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Realtime\Roles\EGlobal as RolesEvent;
use App\Realtime\Users\EGlobal as UsersEvent;
use App\Realtime\Requests\EGlobal as RequestsEvent;
use App\Realtime\Equipments\EGlobal as EquipmentsEvent;
use App\Realtime\RequestTypes\EGlobal as RequestTypesEvent;
use App\Realtime\EquipmentTypes\EGlobal as EquipmentTypesEvent;
use App\Realtime\EquipmentModels\EGlobal as EquipmentModelsEvent;
use App\Realtime\RequestStatuses\EGlobal as RequestStatusesEvent;
use App\Realtime\RequestPriorities\EGlobal as RequestPrioritiesEvent;
use App\Realtime\EquipmentManufacturers\EGlobal as EquipmentManufacturersEvent;

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
     * @return JsonResponse
     */
    public function sync(): JsonResponse
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
        JoinRooms::dispatchAfterResponse($rooms, true);

        return response()->json([
            'rooms' => $rooms,
        ]);
    }
}
