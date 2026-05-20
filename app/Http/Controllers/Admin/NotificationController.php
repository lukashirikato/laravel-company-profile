<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * GET /admin/notifications/feed
     * Return 20 notifikasi terbaru + jumlah unread.
     */
    public function feed(Request $request)
    {
        $items = AdminNotification::orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'icon'       => $n->icon,
                    'color'      => $n->color,
                    'title'      => $n->title,
                    'message'    => $n->message,
                    'data'       => $n->data,
                    'is_read'    => (bool) $n->read_at,
                    'time_human' => $n->created_at?->locale('id')->diffForHumans(),
                    'created_at' => $n->created_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'unread'        => AdminNotification::unread()->count(),
            'notifications' => $items,
        ]);
    }

    /**
     * POST /admin/notifications/{id}/read
     */
    public function markAsRead(int $id)
    {
        $n = AdminNotification::find($id);
        if ($n) {
            $n->markAsRead();
        }
        return response()->json(['ok' => true]);
    }

    /**
     * POST /admin/notifications/read-all
     */
    public function markAllRead()
    {
        AdminNotification::unread()->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }

    /**
     * DELETE /admin/notifications/cleanup
     * Hapus notif lama > 30 hari (dipanggil dari command).
     */
    public function cleanup()
    {
        $deleted = AdminNotification::where('created_at', '<', Carbon::now()->subDays(30))->delete();
        return response()->json(['deleted' => $deleted]);
    }
}
