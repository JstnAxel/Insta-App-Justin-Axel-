@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-base-100 shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold text-center mb-4">Buat Post Baru</h1>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Postingan --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Input Isi Post --}}
        <div class="form-control mb-4">
            <label for="content" class="label font-semibold">Isi Post</label>
            <textarea name="content" id="content" class="textarea textarea-bordered h-24" required></textarea>
            @error('content')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Input Upload Gambar --}}
        <div class="form-control mb-4">
            <label for="image" class="label font-semibold">Upload Gambar (Opsional)</label>
            <input type="file" name="image" id="image" class="file-input file-input-bordered">
            @error('image')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="btn btn-primary w-full">Posting</button>
    </form>
</div>
@endsection
