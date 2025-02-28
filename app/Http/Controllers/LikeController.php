<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        // Cek apakah user sudah like
        $existingLike = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($existingLike) {
            // Jika sudah like, maka unlike
            $existingLike->delete();
        } else {
            // Jika belum like, maka like
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Like status updated!');
    }
}
