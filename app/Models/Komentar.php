<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    protected $fillable = ['id_postingan', 'komentar_data'];
    
    protected $casts = [
        'komentar_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * Get comments for a postingan
     */
    public static function getCommentsForPostingan($postinganId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();
        
        if (!$komentar || empty($komentar->komentar_data)) {
            return [];
        }
        
        // Convert object to array if needed
        $comments = $komentar->komentar_data;
        
        // Sort comments by date (newest first for top-level)
        if (isset($comments['comments']) && is_array($comments['comments'])) {
            usort($comments['comments'], function($a, $b) {
                return strtotime($b['tanggal'] ?? $b['created_at']) - strtotime($a['tanggal'] ?? $a['created_at']);
            });
            
            // Also sort replies within each comment
            foreach ($comments['comments'] as &$comment) {
                if (isset($comment['balasan']) && is_array($comment['balasan'])) {
                    usort($comment['balasan'], function($a, $b) {
                        return strtotime($a['tanggal'] ?? $a['created_at']) - strtotime($b['tanggal'] ?? $b['created_at']);
                    });
                    
                    // Sort nested replies recursively
                    $comment['balasan'] = self::sortNestedReplies($comment['balasan']);
                }
            }
        }
        
        return $comments;
    }
    
    /**
     * Sort nested replies by date (oldest first for replies)
     */
    private static function sortNestedReplies($replies)
    {
        if (!is_array($replies)) return $replies;
        
        usort($replies, function($a, $b) {
            return strtotime($a['tanggal'] ?? $a['created_at']) - strtotime($b['tanggal'] ?? $b['created_at']);
        });
        
        foreach ($replies as &$reply) {
            if (isset($reply['balasan']) && is_array($reply['balasan'])) {
                $reply['balasan'] = self::sortNestedReplies($reply['balasan']);
            }
        }
        
        return $replies;
    }
    
    /**
     * Add a new comment
     */
    public static function addComment($postinganId, $userId, $commentText, $parentId = null, $replyToId = null)
    {
        $komentar = self::firstOrCreate(
            ['id_postingan' => $postinganId],
            ['komentar_data' => ['comments' => []]]
        );
        
        $comments = $komentar->komentar_data;
        
        $newComment = [
            'id_komentar' => self::generateUniqueId(),
            'id_user' => $userId,
            'komentar' => $commentText,
            'tanggal' => now()->toISOString(),
            'created_at' => now()->toISOString(),
        ];
        
        if ($parentId && $replyToId) {
            // Add as nested reply
            $comments = self::addNestedReply($comments, $parentId, $replyToId, $newComment);
        } elseif ($parentId) {
            // Add as reply to a comment
            $comments = self::addReply($comments, $parentId, $newComment);
        } else {
            // Add as top-level comment
            if (!isset($comments['comments'])) {
                $comments['comments'] = [];
            }
            $comments['comments'][] = $newComment;
        }
        
        $komentar->komentar_data = $comments;
        $komentar->save();
        
        return $newComment;
    }
    
    /**
     * Add a reply to a comment
     */
    private static function addReply($comments, $parentId, $newComment)
    {
        if (!isset($comments['comments'])) {
            return $comments;
        }
        
        foreach ($comments['comments'] as &$comment) {
            if ($comment['id_komentar'] == $parentId) {
                if (!isset($comment['balasan'])) {
                    $comment['balasan'] = [];
                }
                $comment['balasan'][] = $newComment;
                return $comments;
            }
            
            // Check nested replies
            if (isset($comment['balasan'])) {
                $result = self::addNestedReplyRecursive($comment['balasan'], $parentId, $newComment);
                if ($result !== false) {
                    $comment['balasan'] = $result;
                    return $comments;
                }
            }
        }
        
        return $comments;
    }
    
    /**
     * Add nested reply
     */
    private static function addNestedReply($comments, $parentId, $replyToId, $newComment)
    {
        if (!isset($comments['comments'])) {
            return $comments;
        }
        
        foreach ($comments['comments'] as &$comment) {
            if ($comment['id_komentar'] == $replyToId) {
                if (!isset($comment['balasan'])) {
                    $comment['balasan'] = [];
                }
                $comment['balasan'][] = $newComment;
                return $comments;
            }
            
            if (isset($comment['balasan'])) {
                $result = self::addNestedReplyRecursive($comment['balasan'], $replyToId, $newComment);
                if ($result !== false) {
                    $comment['balasan'] = $result;
                    return $comments;
                }
            }
        }
        
        return $comments;
    }
    
    /**
     * Recursive helper for nested replies
     */
    private static function addNestedReplyRecursive(&$replies, $targetId, $newComment)
    {
        foreach ($replies as &$reply) {
            if ($reply['id_komentar'] == $targetId) {
                if (!isset($reply['balasan'])) {
                    $reply['balasan'] = [];
                }
                $reply['balasan'][] = $newComment;
                return $replies;
            }
            
            if (isset($reply['balasan'])) {
                $result = self::addNestedReplyRecursive($reply['balasan'], $targetId, $newComment);
                if ($result !== false) {
                    $reply['balasan'] = $result;
                    return $replies;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Update a comment
     */
    public static function updateComment($postinganId, $commentId, $userId, $newText)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();
        if (!$komentar) return false;
        
        $comments = $komentar->komentar_data;
        $updated = false;
        
        if (isset($comments['comments'])) {
            foreach ($comments['comments'] as &$comment) {
                if ($comment['id_komentar'] == $commentId && $comment['id_user'] == $userId) {
                    $comment['komentar'] = $newText;
                    $updated = true;
                    break;
                }
                
                if (isset($comment['balasan'])) {
                    $result = self::updateNestedReply($comment['balasan'], $commentId, $userId, $newText);
                    if ($result !== false) {
                        $comment['balasan'] = $result;
                        $updated = true;
                        break;
                    }
                }
            }
        }
        
        if ($updated) {
            $komentar->komentar_data = $comments;
            $komentar->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Update nested reply
     */
    private static function updateNestedReply(&$replies, $commentId, $userId, $newText)
    {
        foreach ($replies as &$reply) {
            if ($reply['id_komentar'] == $commentId && $reply['id_user'] == $userId) {
                $reply['komentar'] = $newText;
                return $replies;
            }
            
            if (isset($reply['balasan'])) {
                $result = self::updateNestedReply($reply['balasan'], $commentId, $userId, $newText);
                if ($result !== false) {
                    $reply['balasan'] = $result;
                    return $replies;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Delete a comment
     */
    public static function deleteComment($postinganId, $commentId, $userId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();
        if (!$komentar) return false;
        
        $comments = $komentar->komentar_data;
        $deleted = false;
        
        if (isset($comments['comments'])) {
            // Check if it's a top-level comment
            foreach ($comments['comments'] as $key => $comment) {
                if ($comment['id_komentar'] == $commentId) {
                    if ($comment['id_user'] == $userId) {
                        // Delete entire thread if user owns the parent comment
                        unset($comments['comments'][$key]);
                        $comments['comments'] = array_values($comments['comments']);
                        $deleted = true;
                    }
                    break;
                }
                
                // Check in replies
                if (isset($comment['balasan'])) {
                    $result = self::deleteNestedReply($comment['balasan'], $commentId, $userId);
                    if ($result !== false) {
                        $comment['balasan'] = $result;
                        $deleted = true;
                        break;
                    }
                }
            }
        }
        
        if ($deleted) {
            $komentar->komentar_data = $comments;
            $komentar->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete a single reply (not affecting parent)
     */
    public static function deleteSingleReply($postinganId, $commentId, $userId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();
        if (!$komentar) return false;
        
        $comments = $komentar->komentar_data;
        $deleted = false;
        
        if (isset($comments['comments'])) {
            foreach ($comments['comments'] as &$comment) {
                if (isset($comment['balasan'])) {
                    $result = self::deleteSingleNestedReply($comment['balasan'], $commentId, $userId);
                    if ($result !== false) {
                        $comment['balasan'] = $result;
                        $deleted = true;
                        break;
                    }
                }
            }
        }
        
        if ($deleted) {
            $komentar->komentar_data = $comments;
            $komentar->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete nested reply (entire thread)
     */
    private static function deleteNestedReply(&$replies, $commentId, $userId)
    {
        foreach ($replies as $key => &$reply) {
            if ($reply['id_komentar'] == $commentId) {
                if ($reply['id_user'] == $userId) {
                    unset($replies[$key]);
                    $replies = array_values($replies);
                    return $replies;
                }
                break;
            }
            
            if (isset($reply['balasan'])) {
                $result = self::deleteNestedReply($reply['balasan'], $commentId, $userId);
                if ($result !== false) {
                    $reply['balasan'] = $result;
                    return $replies;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Delete single nested reply (just that comment, keep replies)
     */
    private static function deleteSingleNestedReply(&$replies, $commentId, $userId)
    {
        foreach ($replies as $key => &$reply) {
            if ($reply['id_komentar'] == $commentId) {
                if ($reply['id_user'] == $userId) {
                    // If this reply has its own replies, move them up one level
                    if (isset($reply['balasan']) && !empty($reply['balasan'])) {
                        $children = $reply['balasan'];
                        unset($replies[$key]);
                        $replies = array_values($replies);
                        // Insert children at the same position
                        array_splice($replies, $key, 0, $children);
                    } else {
                        unset($replies[$key]);
                        $replies = array_values($replies);
                    }
                    return $replies;
                }
                break;
            }
            
            if (isset($reply['balasan'])) {
                $result = self::deleteSingleNestedReply($reply['balasan'], $commentId, $userId);
                if ($result !== false) {
                    $reply['balasan'] = $result;
                    return $replies;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Generate unique ID for comment
     */
    private static function generateUniqueId()
    {
        return time() . rand(1000, 9999) . rand(100, 999);
    }
    
    /**
     * Get total comment count for a postingan
     */
    public static function getCommentCount($postinganId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();
        if (!$komentar || empty($komentar->komentar_data)) {
            return 0;
        }
        
        $comments = $komentar->komentar_data;
        $count = 0;
        
        if (isset($comments['comments'])) {
            foreach ($comments['comments'] as $comment) {
                $count++;
                if (isset($comment['balasan'])) {
                    $count += self::countNestedReplies($comment['balasan']);
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Count nested replies recursively
     */
    private static function countNestedReplies($replies)
    {
        $count = count($replies);
        foreach ($replies as $reply) {
            if (isset($reply['balasan'])) {
                $count += self::countNestedReplies($reply['balasan']);
            }
        }
        return $count;
    }
}