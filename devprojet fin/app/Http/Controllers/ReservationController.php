<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Log;

class ReservationController extends Controller
{
    public function create($resource_id)
    {
        $resource = Resource::with('category')->findOrFail($resource_id);
        
        if($resource->status !== 'active') {
            return redirect()->route('internal.catalogue')->with('error', 'Cette ressource n\'est pas disponible pour la réservation.');
        }

        return view('user.reservations.create', compact('resource'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'required|string'
        ]);

        $resource = Resource::findOrFail($request->resource_id);

        // Check for conflicts with existing approved/active reservations
        $reservationConflict = Reservation::where('resource_id', $resource->id)
            ->whereIn('status', ['approved', 'active'])
            ->where(function($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($reservationConflict) {
            return back()->withInput()->with('error', 'La ressource est déjà réservée sur ce créneau.');
        }

        // Check for conflicts with maintenance periods
        $maintenanceConflict = \App\Models\Maintenance::where('resource_id', $resource->id)
            ->where('status', 'scheduled') // Assuming 'scheduled' is the status for active maintenances
            ->where(function($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($maintenanceConflict) {
            return back()->withInput()->with('error', 'La ressource est en maintenance sur ce créneau.');
        }

        Reservation::create([
            'resource_id' => $resource->id,
            'user_id' => Auth::id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending' // Default status
        ]);

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'reservation_created',
            'target' => 'Resource ID ' . $resource->id,
            'details' => json_encode(['start' => $request->start_time, 'end' => $request->end_time, 'reason' => $request->reason]),
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('internal.dashboard')->with('success', 'Votre demande de réservation a été enregistrée.');
    }
}