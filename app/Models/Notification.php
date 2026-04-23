<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'data',
        'priority',
        'read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    public static function markAllAsRead()
    {
        return self::unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    public static function getUnreadCount()
    {
        return cache()->remember('notifications:unread_count', 60, function () {
            return self::unread()->count();
        });
    }

    public static function clearUnreadCache()
    {
        cache()->forget('notifications:unread_count');
    }
}