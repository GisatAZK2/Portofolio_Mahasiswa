<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LikedPostingan;
use App\Models\Postingan;
use Illuminate\Support\Facades\Auth;

class LikedPostinganController extends Controller
{
    /**
     * Toggle like/unlike for a postingan
     */
    public function toggle(Request $request)
    {
        // Ambil id_postingan dari query parameter atau request body
        $postinganId = $request->query('id') ?? $request->input('id_postingan');
        
        if (!$postinganId) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan ID is required',
            ], 400);
        }
        
        // Validate postingan exists
        $request->merge(['id_postingan' => $postinganId]);
        $request->validate([
            'id_postingan' => 'required|exists:postingan,id_postingan',
        ]);

        // 🔥 Ambil postingan
        $post = Postingan::findOrFail($postinganId);

        // 🚫 Cegah like postingan sendiri
        if ($post->id_user === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Lu gak bisa nge-like postingan sendiri 😏',
            ], 403);
        }

        $existingLike = LikedPostingan::where('id_user', Auth::id())
            ->where('id_postingan', $postinganId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            LikedPostingan::create([
                'id_user' => Auth::id(),
                'id_postingan' => $postinganId,
            ]);
            $liked = true;
        }

        $likeCount = LikedPostingan::where('id_postingan', $postinganId)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
        ]);
    }
}