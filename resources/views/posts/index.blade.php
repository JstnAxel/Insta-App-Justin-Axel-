@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-6">
    <h1 class="text-3xl font-bold text-center mb-6">Daftar Post</h1>

    {{-- Alert sukses (Toast) --}}
    @if (session('success'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-success text-white">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Tombol Buat Post --}}
    <div class="text-center mb-6">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Post</a>
    </div>

    {{-- Looping Post --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($posts as $post)
            <div class="card card-compact bg-base-100 w-72 shadow-md">
                <div class="p-4">
                    <h2 class="card-title text-sm font-bold">{{ $post->user->name }}</h2>
                    <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>

                {{-- Gambar Post --}}
                @if($post->image)
                    <figure>
                        <img src="{{ asset('storage/' . $post->image) }}" class=" h-40 w-full object-cover rounded-t-lg">
                    </figure>
                @endif

                <div class="card-body p-4">
                    <p class="text-sm">{{ $post->content }}</p>

                    @if (Auth::id() === $post->user_id)
                        <div class="card-actions justify-end">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus post ini?')" class="btn btn-sm btn-error">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif   
                    <form action="{{ route('likes.toggle', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $post->isLikedByUser() ? 'btn-error' : 'btn-primary' }}">
                            ❤️ {{ $post->likes->count() }} Like
                        </button>
                    </form>
                    <!-- Tombol Komentar -->
<button onclick="document.getElementById('modal-{{ $post->id }}').showModal()" class="btn btn-sm btn-secondary">
    💬 {{ $post->comments->count() }} Komentar
</button>

<!-- Modal Komentar -->
<dialog id="modal-{{ $post->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Komentar</h3>

        <!-- Form Tambah Komentar -->
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
            @csrf
            <textarea name="content" class="textarea textarea-bordered w-full" placeholder="Tambahkan komentar..." required></textarea>
            <button type="submit" class="btn btn-primary mt-2">Kirim</button>
        </form>

        <!-- Daftar Komentar -->
        <div>
            @foreach ($post->comments->whereNull('parent_id') as $comment)
                <div class="border p-2 rounded-lg mb-2">
                    <p class="text-sm"><strong>{{ $comment->user->name }}</strong> - {{ $comment->created_at->diffForHumans() }}</p>
                    <p class="text-sm">{{ $comment->content }}</p>

                    <!-- Tombol Balas -->
                    <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-blue-500 text-xs">Balas</button>

                    <!-- Form Balas Komentar -->
                    <form id="reply-{{ $comment->id }}" action="{{ route('comments.store', $post->id) }}" method="POST" class="hidden mt-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" class="textarea textarea-bordered w-full text-sm" placeholder="Tulis balasan..." required></textarea>
                        <button type="submit" class="btn btn-xs btn-primary mt-1">Balas</button>
                    </form>

                    <!-- Tampilkan Balasan -->
                    @foreach ($comment->replies as $reply)
                        <div class="ml-4 border-l-2 pl-2 mt-2">
                            <p class="text-xs"><strong>{{ $reply->user->name }}</strong> - {{ $reply->created_at->diffForHumans() }}</p>
                            <p class="text-xs">{{ $reply->content }}</p>

                            @if ($reply->user_id === Auth::id() || $post->user_id === Auth::id())
                                <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs">Hapus</button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                    <!-- Hapus Komentar -->
                    @if ($comment->user_id === Auth::id() || $post->user_id === Auth::id())
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs">Hapus</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Tombol Tutup Modal -->
        <div class="modal-action">
            <button onclick="document.getElementById('modal-{{ $post->id }}').close()" class="btn">Tutup</button>
        </div>
    </div>
</dialog>

                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
