<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReservationUpdated;
use App\Models\Log;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $managerId = Auth::id();
        $supervisedResourcesIds = Resource::where('manager_id', $managerId)->pluck('id');

        $pendingRequests = Reservation::with('user', 'resource')
            ->whereIn('resource_id', $supervisedResourcesIds)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $myResourcesCount = Resource::where('manager_id', $managerId)->count();
        $activeReservationsCount = Reservation::whereIn('resource_id', $supervisedResourcesIds)
            ->where('status', 'active')
            ->count();
            
        $pendingIncidentsCount = Incident::whereIn('resource_id', $supervisedResourcesIds)
            ->where('status', '!=', 'resolved')
            ->count();

        $totalReservationsCount = Reservation::whereIn('resource_id', $supervisedResourcesIds)->count();

        return view('manager.dashboard', compact('pendingRequests', 'myResourcesCount', 'activeReservationsCount', 'pendingIncidentsCount', 'totalReservationsCount'));
    }

    public function approveReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Ensure the manager supervises this resource
        if ($reservation->resource->manager_id != Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à gérer cette réservation.');
        }

        $reservation->update(['status' => 'approved']);
        
        // Notification
        $reservation->user->notify(new ReservationUpdated($reservation, 'approuvée'));

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'reservation_approved',
            'target' => 'Reservation ID ' . $reservation->id,
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Réservation approuvée.');
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->resource->manager_id != Auth::id()) {
            return back()->with('error', 'Interdit.');
        }

        $reservation->update(['status' => 'rejected']);

        // Notification
        $reservation->user->notify(new ReservationUpdated($reservation, 'refusée'));

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'reservation_rejected',
            'target' => 'Reservation ID ' . $reservation->id,
            'result' => 'rejected', // Adjusted to match schema if needed or just use separate field
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Réservation refusée.');
    }

    public function myResources()
    {
        $resources = Resource::where('manager_id', Auth::id())
            ->with(['category', 'activeReservation'])
            ->paginate(10);

        return view('manager.resources', compact('resources'));
    }

    public function incidents()
    {
        $supervisedResourcesIds = Resource::where('manager_id', Auth::id())->pluck('id');
        
        $incidents = Incident::whereIn('resource_id', $supervisedResourcesIds)
            ->with(['user', 'resource'])
            ->latest()
            ->paginate(10);
            
        return view('manager.incidents', compact('incidents'));
    }

    public function resolveIncident($id)
    {
        $incident = Incident::findOrFail($id);
        
        if ($incident->resource->manager_id != Auth::id()) {
            return back()->with('error', 'Non autorisé.');
        }

        $incident->update(['status' => 'resolved']);
        
        // Notify user? Optional but good.
        
        return back()->with('success', 'Incident marqué comme résolu.');
    }
}