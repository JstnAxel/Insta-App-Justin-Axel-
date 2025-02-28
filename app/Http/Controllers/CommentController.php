<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
    
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);
    
        return response()->json([
            'id' => $comment->id,
            'user' => $comment->user->name,
            'user_id' => $comment->user_id,
            'content' => $comment->content,
            'created_at' => $comment->created_at->diffForHumans(),
        ]);
    }
    
    
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->delete();
        return response()->json(['success' => true, 'comment_id' => $comment->id]);
    }
    }
