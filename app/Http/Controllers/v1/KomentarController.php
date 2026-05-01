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
        return response()->json([
            'success' => false,
            'message' => 'ID Postingan is required'
        ], 400);
    }

    try {
        // ✅ Ambil $komentar dulu untuk dapat updated_at
        $komentar     = Komentar::where('id_postingan', $id_postingan)->first();
        $commentsData = Komentar::getCommentsForPostingan($id_postingan);
        $comments     = isset($commentsData['comments']) ? $commentsData['comments'] : [];

        $comments = $this->loadUserDataForComments($comments);

        return response()->json([
            'success'      => true,
            'comments'     => $comments,
            'last_updated' => $komentar?->updated_at?->timestamp, // ✅
            'total_count'  => Komentar::getCommentCount($id_postingan)
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
        // Pastikan user sudah login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        try {
            $validated = $request->validate([
                'id_postingan' => 'required|exists:postingan,id_postingan',
                'komentar'     => 'required|string|max:1000',
                'parent_id'    => 'nullable|integer',
                'reply_to_id'  => 'nullable|integer',
            ]);

            $comment = Komentar::addComment(
                $validated['id_postingan'],
                Auth::id(),
                $validated['komentar'],
                $validated['parent_id'] ?? null,
                $validated['reply_to_id'] ?? null
            );

            // Load user data for the new comment
            $comment = $this->loadUserDataForComment((array) $comment);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan!',
                'comment' => $comment,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error storing comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a comment
     */

    public function update(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Anda harus login terlebih dahulu'], 401);
    }

    $id          = $request->query('id');
    $postinganId = $request->query('id_postingan');
    $lastUpdated = $request->query('last_updated');

    if (!$id || !$postinganId) {
        return response()->json(['success' => false, 'message' => 'Comment ID and Postingan ID are required'], 400);
    }

    try {
        $request->validate(['komentar' => 'required|string|max:1000']);

        // ✅ Cek stale data SEBELUM panggil model (tanpa nested transaction)
        $komentar = Komentar::where('id_postingan', $postinganId)->first();
        if (!$komentar) {
            return response()->json(['success' => false, 'message' => 'Data komentar tidak ditemukan'], 404);
        }
        if ($lastUpdated && $komentar->updated_at->timestamp > (int)$lastUpdated) {
            return response()->json([
                'success' => false,
                'code'    => 'STALE_DATA',
                'message' => 'Komentar telah diperbarui oleh pengguna lain. Memuat ulang...'
            ], 409);
        }

        $updated = Komentar::updateComment($postinganId, $id, Auth::id(), $request->komentar);

        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Komentar tidak ditemukan atau anda tidak memiliki akses'], 403);
        }

        return response()->json(['success' => true, 'message' => 'Komentar berhasil diperbarui!']);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error updating comment: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}

public function destroy(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Anda harus login terlebih dahulu'], 401);
    }

    $id          = $request->query('id');
    $postinganId = $request->query('id_postingan');
    $type        = $request->query('type', 'full');
    $lastUpdated = $request->query('last_updated');

    if (!$id || !$postinganId) {
        return response()->json(['success' => false, 'message' => 'Comment ID and Postingan ID are required'], 400);
    }

    try {
        // ✅ Cek stale data SEBELUM panggil model
        $komentar = Komentar::where('id_postingan', $postinganId)->first();
        if (!$komentar) {
            return response()->json(['success' => false, 'message' => 'Data komentar tidak ditemukan'], 404);
        }
        if ($lastUpdated && $komentar->updated_at->timestamp > (int)$lastUpdated) {
            return response()->json([
                'success' => false,
                'code'    => 'STALE_DATA',
                'message' => 'Komentar telah diperbarui oleh pengguna lain. Memuat ulang...'
            ], 409);
        }

        $deleted = $type === 'single'
            ? Komentar::deleteSingleReply($postinganId, $id, Auth::id())
            : Komentar::deleteComment($postinganId, $id, Auth::id());

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Komentar tidak ditemukan atau anda tidak memiliki akses'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => $type === 'single' ? 'Balasan komentar berhasil dihapus!' : 'Komentar berhasil dihapus!'
        ]);

    } catch (\Exception $e) {
        \Log::error('Error deleting comment: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}
    

}