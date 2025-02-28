@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Post</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Post</a>
    <ul>
        @foreach($posts as $post)
        <li>
            <strong>{{ $post->user->name }}</strong> 
            <br> {{ $post->content }}
            @if($post->image)
                <br><img src="{{ asset('storage/' . $post->image) }}" width="100">
            @endif
            <br>
          

                   
        @endforeach
    </ul>
</div>
@endsection
