<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    protected $fillable = ['id_postingan', 'komentar_data'];

    protected $casts = [
        'komentar_data' => 'array',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ==================== READ ====================

    public static function getCommentsForPostingan($postinganId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();

        if (!$komentar || empty($komentar->komentar_data)) {
            return [];
        }

        $comments = $komentar->komentar_data;

        if (isset($comments['comments']) && is_array($comments['comments'])) {
            usort($comments['comments'], function ($a, $b) {
                return strtotime($b['tanggal'] ?? $b['created_at'])
                     - strtotime($a['tanggal'] ?? $a['created_at']);
            });

            foreach ($comments['comments'] as &$comment) {
                if (isset($comment['balasan']) && is_array($comment['balasan'])) {
                    $comment['balasan'] = self::sortNestedReplies($comment['balasan']);
                }
            }
            unset($comment);
        }

        return $comments;
    }

    private static function sortNestedReplies($replies)
    {
        if (!is_array($replies)) return $replies;

        usort($replies, function ($a, $b) {
            return strtotime($a['tanggal'] ?? $a['created_at'])
                 - strtotime($b['tanggal'] ?? $b['created_at']);
        });

        foreach ($replies as &$reply) {
            if (isset($reply['balasan']) && is_array($reply['balasan'])) {
                $reply['balasan'] = self::sortNestedReplies($reply['balasan']);
            }
        }
        unset($reply);

        return $replies;
    }

    // ==================== CREATE ====================

    public static function addComment($postinganId, $userId, $commentText, $parentId = null, $replyToId = null)
    {
        return DB::transaction(function () use ($postinganId, $userId, $commentText, $parentId, $replyToId) {
            $komentar = self::where('id_postingan', $postinganId)
                ->lockForUpdate()
                ->first();

            if (!$komentar) {
                $komentar = self::create([
                    'id_postingan'  => $postinganId,
                    'komentar_data' => ['comments' => []],
                ]);
            }

            $comments = $komentar->komentar_data ?? ['comments' => []];

            $newComment = [
                'id_komentar' => self::generateUniqueId(),
                'id_user'     => $userId,
                'komentar'    => $commentText,
                'tanggal'     => now()->toISOString(),
                'created_at'  => now()->toISOString(),
            ];

            if ($parentId && $replyToId) {
                $comments = self::addNestedReply($comments, $parentId, $replyToId, $newComment);
            } elseif ($parentId) {
                $comments = self::addReply($comments, $parentId, $newComment);
            } else {
                if (!isset($comments['comments'])) {
                    $comments['comments'] = [];
                }
                $comments['comments'][] = $newComment;
            }

            $komentar->komentar_data = $comments;
            $komentar->save();

            return $newComment;
        });
    }

    private static function addReply($comments, $parentId, $newComment)
    {
        if (!isset($comments['comments'])) return $comments;

        foreach ($comments['comments'] as &$comment) {
            if ($comment['id_komentar'] == $parentId) {
                if (!isset($comment['balasan'])) $comment['balasan'] = [];
                $comment['balasan'][] = $newComment;
                return $comments;
            }

            if (isset($comment['balasan'])) {
                $result = self::addNestedReplyRecursive($comment['balasan'], $parentId, $newComment);
                if ($result !== false) {
                    $comment['balasan'] = $result;
                    return $comments;
                }
            }
        }
        unset($comment);

        return $comments;
    }

    private static function addNestedReply($comments, $parentId, $replyToId, $newComment)
    {
        if (!isset($comments['comments'])) return $comments;

        foreach ($comments['comments'] as &$comment) {
            if ($comment['id_komentar'] == $replyToId) {
                if (!isset($comment['balasan'])) $comment['balasan'] = [];
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
        unset($comment);

        return $comments;
    }

    private static function addNestedReplyRecursive(&$replies, $targetId, $newComment)
    {
        foreach ($replies as &$reply) {
            if ($reply['id_komentar'] == $targetId) {
                if (!isset($reply['balasan'])) $reply['balasan'] = [];
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
        unset($reply);

        return false;
    }

    // ==================== UPDATE ====================

    public static function updateComment($postinganId, $commentId, $userId, $newText)
    {
        return DB::transaction(function () use ($postinganId, $commentId, $userId, $newText) {
            $komentar = self::where('id_postingan', $postinganId)
                ->lockForUpdate()
                ->first();

            if (!$komentar) return false;

            $comments = $komentar->komentar_data;
            $updated  = false;

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
                unset($comment);
            }

            if ($updated) {
                $komentar->komentar_data = $comments;
                $komentar->save();
                return true;
            }

            return false;
        });
    }

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
        unset($reply);

        return false;
    }

    // ==================== DELETE ====================

    public static function deleteComment($postinganId, $commentId, $userId)
    {
        return DB::transaction(function () use ($postinganId, $commentId, $userId) {
            $komentar = self::where('id_postingan', $postinganId)
                ->lockForUpdate()
                ->first();

            if (!$komentar) return false;

            $comments = $komentar->komentar_data;
            $deleted  = false;

            if (isset($comments['comments'])) {
                foreach ($comments['comments'] as $key => &$comment) {
                    if ($comment['id_komentar'] == $commentId) {
                        if ($comment['id_user'] == $userId) {
                            unset($comments['comments'][$key]);
                            $comments['comments'] = array_values($comments['comments']);
                            $deleted = true;
                        }
                        break;
                    }

                    if (isset($comment['balasan'])) {
                        $result = self::deleteNestedReply($comment['balasan'], $commentId, $userId);
                        if ($result !== false) {
                            $comment['balasan'] = $result;
                            $deleted = true;
                            break;
                        }
                    }
                }
                unset($comment);
            }

            if ($deleted) {
                $komentar->komentar_data = $comments;
                $komentar->save();
                return true;
            }

            return false;
        });
    }

    public static function deleteSingleReply($postinganId, $commentId, $userId)
    {
        return DB::transaction(function () use ($postinganId, $commentId, $userId) {
            $komentar = self::where('id_postingan', $postinganId)
                ->lockForUpdate()
                ->first();

            if (!$komentar) return false;

            $comments = $komentar->komentar_data;
            $deleted  = false;

            if (isset($comments['comments'])) {
                foreach ($comments['comments'] as &$comment) {
                    // Cek juga jika yang dihapus adalah top-level comment (bukan di balasan)
                    if ($comment['id_komentar'] == $commentId && $comment['id_user'] == $userId) {
                        // Treat sama seperti deleteComment untuk top-level
                        break;
                    }

                    if (isset($comment['balasan'])) {
                        $result = self::deleteSingleNestedReply($comment['balasan'], $commentId, $userId);
                        if ($result !== false) {
                            $comment['balasan'] = $result;
                            $deleted = true;
                            break;
                        }
                    }
                }
                unset($comment);
            }

            if ($deleted) {
                $komentar->komentar_data = $comments;
                $komentar->save();
                return true;
            }

            return false;
        });
    }

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
        unset($reply);

        return false;
    }

    private static function deleteSingleNestedReply(&$replies, $commentId, $userId)
    {
        foreach ($replies as $key => &$reply) {
            if ($reply['id_komentar'] == $commentId) {
                if ($reply['id_user'] == $userId) {
                    if (isset($reply['balasan']) && !empty($reply['balasan'])) {
                        $children = $reply['balasan'];
                        unset($replies[$key]);
                        $replies = array_values($replies);
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
        unset($reply);

        return false;
    }

    // ==================== HELPERS ====================

    private static function generateUniqueId()
    {
        return time() . rand(1000, 9999) . rand(100, 999);
    }

    public static function getCommentCount($postinganId)
    {
        $komentar = self::where('id_postingan', $postinganId)->first();

        if (!$komentar || empty($komentar->komentar_data)) return 0;

        $comments = $komentar->komentar_data;
        $count    = 0;

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