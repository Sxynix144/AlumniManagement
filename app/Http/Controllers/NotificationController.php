<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** Return unread count as JSON (for navbar bell) */
    public function unreadCount()
    {
        $count = NotificationLog::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /** Full notifications list page */
    public function index()
    {
        $notifications = NotificationLog::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        // Mark all as read when viewing the page
        NotificationLog::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark single notification as read and redirect */
    public function markRead(NotificationLog $notification)
    {
        abort_if($notification->user_id !== auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return redirect($notification->link ?? route('notifications.index'));
    }

    /** Mark all as read via AJAX */
    public function markAllRead()
    {
        NotificationLog::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Delete a single notification */
    public function destroy(NotificationLog $notification)
    {
        abort_if($notification->user_id !== auth()->id(), 403);
        $notification->delete();
        return back()->with('success', 'Notification removed.');
    }
}
