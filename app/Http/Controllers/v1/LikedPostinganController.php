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
    public function toggle(Request $request, $postingan)
{
    $request->merge(['id_postingan' => $postingan]);
    $request->validate([
        'id_postingan' => 'required|exists:postingan,id_postingan',
    ]);

    // 🔥 Ambil postingan
    $post = Postingan::findOrFail($postingan);

    // 🚫 Cegah like postingan sendiri
    if ($post->id_user === Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Lu gak bisa nge-like postingan sendiri 😏',
        ], 403);
    }

    $existingLike = LikedPostingan::where('id_user', Auth::id())
        ->where('id_postingan', $postingan)
        ->first();

    if ($existingLike) {
        $existingLike->delete();
        $liked = false;
    } else {
        LikedPostingan::create([
            'id_user' => Auth::id(),
            'id_postingan' => $postingan,
        ]);
        $liked = true;
    }

    $likeCount = LikedPostingan::where('id_postingan', $postingan)->count();

    return response()->json([
        'success' => true,
        'liked' => $liked,
        'like_count' => $likeCount,
    ]);
}
}