<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Komentar ditambahkan!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id === Auth::id() || $comment->post->user_id === Auth::id()) {
            $comment->delete();
            return redirect()->back()->with('success', 'Komentar dihapus!');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin menghapus komentar ini!');
    }
}
