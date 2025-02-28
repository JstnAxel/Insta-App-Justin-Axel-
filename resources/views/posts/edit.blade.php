@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-base-100 shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold text-center mb-4">Edit Post</h1>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Input Konten --}}
        <div class="form-control mb-4">
            <label for="content" class="label font-semibold">Konten</label>
            <textarea id="content" name="content" class="textarea textarea-bordered h-24" required>{{ $post->content }}</textarea>
        </div>

        {{-- Tampilkan Gambar Lama Jika Ada --}}
        @if ($post->image)
            <div class="mb-4">
                <label class="label font-semibold">Gambar Lama</label>
                <img src="{{ asset('storage/' . $post->image) }}" alt="Gambar Post" class="rounded-lg w-full">
            </div>
        @endif

        {{-- Input Gambar Baru (Opsional) --}}
        <div class="form-control mb-4">
            <label for="image" class="label font-semibold">Ganti Gambar (Opsional)</label>
            <input type="file" id="image" name="image" class="file-input file-input-bordered">
        </div>

        {{-- Tombol Update & Batal --}}
        <div class="flex justify-between">
            <button type="submit" class="btn btn-primary w-1/2">Update Post</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary w-1/2 ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection
