<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class InternalUserController extends Controller
{
    public function dashboard()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['resource', 'resource.category'])
            ->latest()
            ->take(5)
            ->get();
            
        $activeReservationsCount = Reservation::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->count();

        $approvedReservationsThisMonth = Reservation::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalReservationsCount = Reservation::where('user_id', Auth::id())->count();
        $rejectedReservationsCount = Reservation::where('user_id', Auth::id())->where('status', 'rejected')->count();

        return view('user.dashboard', compact('reservations', 'activeReservationsCount', 'approvedReservationsThisMonth', 'totalReservationsCount', 'rejectedReservationsCount'));
    }

    public function catalogue()
    {
        $resources = \App\Models\Resource::with('category')
            ->where('status', 'active')
            ->get();
            
        return view('user.catalogue', compact('resources'));
    }

    public function reservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['resource'])
            ->latest()
            ->paginate(10);
            
        return view('user.reservations.index', compact('reservations'));
    }
}
