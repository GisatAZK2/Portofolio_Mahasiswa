<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komentar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    /**
     * Display comments for a postingan
     */
    public function index(Request $request)
    {
        $id_postingan = $request->query('id_postingan');
        
        if (!$id_postingan) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Postingan is required'
                ], 400);
            }
            abort(404);
        }
        
        try {
            $commentsData = Komentar::getCommentsForPostingan($id_postingan);
            $comments = isset($commentsData['comments']) ? $commentsData['comments'] : [];
            
            // Load user data for all comments
            $comments = $this->loadUserDataForComments($comments);
            
            return response()->json([
                'success' => true,
                'comments' => $comments,
                'total_count' => Komentar::getCommentCount($id_postingan)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Load user data for all comments recursively
     */
    private function loadUserDataForComments($comments)
    {
        if (!is_array($comments)) {
            return [];
        }
        
        foreach ($comments as &$comment) {
            $comment = $this->loadUserDataForComment($comment);
            if (isset($comment['balasan']) && is_array($comment['balasan'])) {
                $comment['balasan'] = $this->loadUserDataForComments($comment['balasan']);
            }
        }
        
        return $comments;
    }
    
    private function loadUserDataForComment($comment)
    {
        if (isset($comment['id_user'])) {
            $user = User::find($comment['id_user']);
            if ($user) {
                $comment['user'] = [
                    'id' => $user->id,
                    'nama_mahasiswa' => $user->nama_mahasiswa,
                    'username' => $user->username,
                    'photo_profile' => $user->photo_profile,
                ];
            } else {
                $comment['user'] = [
                    'id' => null,
                    'nama_mahasiswa' => 'User Tidak Dikenal',
                    'username' => null,
                    'photo_profile' => null,
                ];
            }
        } else {
            $comment['user'] = [
                'id' => null,
                'nama_mahasiswa' => 'User Tidak Dikenal',
                'username' => null,
                'photo_profile' => null,
            ];
        }
        
        return $comment;
    }
    
    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_postingan' => 'required|exists:postingan,id_postingan',
                'komentar' => 'required|string|max:1000',
                'parent_id' => 'nullable|integer',
                'reply_to_id' => 'nullable|integer',
            ]);
            
            $comment = Komentar::addComment(
                $request->id_postingan,
                Auth::id(),
                $request->komentar,
                $request->parent_id,
                $request->reply_to_id
            );
            
            // Load user data for the new comment
            $comment = $this->loadUserDataForComment((array)$comment);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil ditambahkan!',
                    'comment' => $comment,
                ]);
            }
            
            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error storing comment: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
    
    /**
     * Update a comment
     */
    public function update(Request $request)
    {
        $id = $request->query('id');
        $postinganId = $request->query('id_postingan');
        
        if (!$id || !$postinganId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment ID and Postingan ID are required'
                ], 400);
            }
            abort(404);
        }
        
        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);
        
        try {
            $updated = Komentar::updateComment($postinganId, $id, Auth::id(), $request->komentar);
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar tidak ditemukan atau anda tidak memiliki akses'
                ], 403);
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil diperbarui!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Komentar berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a comment
     */
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        $postinganId = $request->query('id_postingan');
        $type = $request->query('type', 'full'); // 'full' or 'single'
        
        if (!$id || !$postinganId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment ID and Postingan ID are required'
                ], 400);
            }
            abort(404);
        }
        
        try {
            if ($type === 'single') {
                $deleted = Komentar::deleteSingleReply($postinganId, $id, Auth::id());
            } else {
                $deleted = Komentar::deleteComment($postinganId, $id, Auth::id());
            }
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar tidak ditemukan atau anda tidak memiliki akses'
                ], 403);
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $type === 'single' ? 'Balasan komentar berhasil dihapus!' : 'Komentar berhasil dihapus!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}