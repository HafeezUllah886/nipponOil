<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\notifications;

class NotificationsController extends Controller
{
    public function get()
    {
        $currentUserId = auth()->id();
        $currentUserWarehouse = auth()->user()->warehouseID;
        $unreadNotifications = notifications::where('warehouseID', auth()->user()->warehouseID)->where(function ($query) use ($currentUserId) {
            $query->whereJsonDoesntContain('read_by', $currentUserId)
                ->orWhereNull('read_by');
        })
        ->get();

        return $unreadNotifications;
    }

    public function markAsRead($id)
    {
        $notification = notifications::find($id);
        $readBy = $notification->read_by ?? [];
        $readBy[] = auth()->id();
        $notification->update(['read_by' => $readBy]);
    }
}
