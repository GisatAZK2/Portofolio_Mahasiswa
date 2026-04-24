<?php

namespace App\Http\Controllers\v1;

use App\Models\Notification;
use Illuminate\Http\Request;

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
        
        $notifications = Notification::orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($notifications);
    }

    public function unreadCount()
    {
        $count = Notification::where('read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->update([
                'read' => true,
                'read_at' => now(),
            ]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        Notification::where('read', false)->update([
            'read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        Notification::truncate();
        return response()->json(['success' => true]);
    }
    
}