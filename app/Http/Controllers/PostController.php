<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

        Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
{
    if (Auth::id() !== $post->user_id) {
        abort(403, 'Unauthorized action.');
    }

    return view('posts.edit', compact('post'));
}


public function update(Request $request, Post $post)
{
    if (Auth::id() !== $post->user_id) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'content' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('image')) {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $imagePath = $request->file('image')->store('posts', 'public');
        $post->image = $imagePath;
    }

    $post->update([
        'content' => $request->content,
        'image' => $post->image, // Pastikan gambar tetap ada
    ]);

    return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui');
}
    

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }
    
        $post->delete();
    
        return redirect()->route('dashboard')->with('success', 'Post berhasil dihapus!');
    }
    
}


