@extends('layouts.user')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Mes Notifications</h1>
        <p class="text-muted">Consultez vos notifications récentes.</p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form action="{{ route('notifications.readAll') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Tout marquer comme lu</button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste des notifications</h2>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr class="{{ $notification->read_at ? 'opacity-50' : 'font-bold' }}">
                    <td>
                        {{ $notification->data['message'] ?? 'Notification' }}
                    </td>
                    <td>
                        {{ $notification->created_at->diffForHumans() }}
                    </td>
                    <td>
                        @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">Marquer comme lu</button>
                        </form>
                        @else
                        <span class="badge badge-secondary">Lu</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4">
                        Aucune notification.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notifications->hasPages())
    <div class="pagination-container p-4">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
