<?php

namespace App\Http\Controllers\v1;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class NotificationController
{
    public static function add($type, $data, $priority = 'normal')
    {
        $notification = Notification::create([
            'type' => $type,
            'data' => $data,
            'priority' => $priority,
            'read' => false,
        ]);

        return $notification;
    }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $notifications = Notification::orderBy('created_at', 'desc')->paginate($perPage);

        $notifications->getCollection()->transform(function ($item) {
            $data = $item->data ?? [];

            if (isset($data['message'])) {
                $data['message'] = autoTranslate($data['message']);
            }

            if (isset($data['title'])) {
                $data['title'] = autoTranslate($data['title']);
            }

            $item->data = $data;

            return $item;
        });

        return response()->json($notifications);
    }

    public function unreadCount()
    {
        $count = Notification::where('read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request)
    {
        try {
            // Ambil id dari query parameter
            $id = $request->query('id');

            if (!$id) {
                return response()->json(['success' => false, 'message' => 'ID notifikasi diperlukan'], 400);
            }

            // Cari notifikasi berdasarkan ID (tanpa user_id filter)
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
            }

            // Update status notifikasi
            $notification->update([
                'read' => true,
                'read_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            // Ambil array ID notifikasi dari request
            $notificationIds = $request->input('notification_ids', []);

            if (empty($notificationIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada notifikasi yang dipilih'
                ], 400);
            }

            // Update hanya notifikasi yang ID-nya dikirim dari frontend
            $updated = Notification::whereIn('id', $notificationIds)
                ->where('read', false)
                ->update([
                    'read' => true,
                    'read_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai dibaca',
                'updated_count' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function clearAll(Request $request)
    {
        try {
            // Ambil array ID notifikasi dari request
            $notificationIds = $request->input('notification_ids', []);

            if (empty($notificationIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada notifikasi yang dipilih'
                ], 400);
            }

            // HAPUS hanya notifikasi yang ID-nya dikirim dari frontend
            $deleted = Notification::whereIn('id', $notificationIds)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi yang dipilih telah dihapus',
                'deleted_count' => $deleted
            ]);

        } catch (\Exception $e) {
            Log::error('Clear all error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}