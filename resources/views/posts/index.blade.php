@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-center mb-6">Daftar Post</h1>

    <div class="text-center mb-6">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Post</a>
    </div>

    @foreach ($posts as $post )
    <div class="flex mt-6 justify-center items-center bg-gray-100">
        <div class="card w-96 bg-white shadow-xl rounded-lg overflow-hidden relative p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="https://i.pravatar.cc/40" class="w-10 h-10 rounded-full" alt="User Profile">
                    <div class="ml-3">
                        <p class="font-semibold">{{ $post->user->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="relative">
                    @if (Auth::id() === $post->user_id)
                    <button class="text-gray-500 hover:text-gray-600 focus:outline-none" onclick="toggleDropdown({{ $post->id }})">⋮</button>
                    <div id="dropdownMenu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-24 bg-white border rounded-lg shadow-lg">
                        <a href="{{ route('posts.edit', $post->id) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Edit</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus post ini?')" class="cursor-pointer">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200">Hapus</button>
                        </form>
                    </div>
                    @endif   
                </div>
            </div>

            @if($post->image)
             <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-64 object-cover mt-3" alt="Post Image">
            @endif              

            <div class="px-4 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        <button id="like-btn-{{ $post->id }}" 
                            class="text-white hover:text-red-600 btn {{ $post->isLikedByUser() ? 'btn-error' : 'btn-primary' }}"
                             onclick="toggleLike({{ $post->id }})" >
                             ❤️<span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>Like
                        </button>
                        <button class="text-gray-500 hover:text-gray-600" onclick="openModal({{ $post->id }})">
                            💬 <span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }} Komentar</span>
                        </button>
                                            </div>
                </div>
            </div>

            <div class="px-4 py-3">
                <p><span class="font-semibold"><span>@</span>{{ $post->user->name }}</span class="">{{ $post->content }}</p>
            </div>
        </div>
    </div>

    <div id="commentModal-{{ $post->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Komentar</h2>

            <form id="comment-form-{{ $post->id }}" class="mb-4">
                <textarea class="textarea textarea-bordered w-full" placeholder="Tambahkan komentar..." required></textarea>
                <button type="button" onclick="addComment({{ $post->id }})" class="btn btn-primary mt-2">Kirim</button>
            </form>

            <div id="comment-list-{{ $post->id }}" class="max-h-60 overflow-y-auto">
                @foreach ($post->comments->whereNull('parent_id') as $comment)
                    <div id="comment-{{ $comment->id }}" class="border p-2 rounded-lg mb-2">
                        <p class="text-sm"><strong>{{ $comment->user->name }}</strong> - {{ $comment->created_at->diffForHumans() }}</p>
                        <p class="text-sm">{{ $comment->content }}</p>

                        <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-blue-500 text-xs">Balas</button>

                        <form id="reply-form-{{ $comment->id }}" class="hidden mt-2">
                            <textarea class="textarea textarea-bordered w-full text-sm" placeholder="Tulis balasan..." required></textarea>
                            <button type="button" onclick="addComment({{ $post->id }}, {{ $comment->id }})" class="btn btn-xs btn-primary mt-1">Balas</button>
                        </form>

                        <div id="replies-{{ $comment->id }}" class="ml-4 border-l-2 pl-2 mt-2">
                            @foreach ($comment->replies as $reply)
                                <div id="comment-{{ $reply->id }}" class="border p-2 rounded-lg mb-2">
                                    <p class="text-xs"><strong>{{ $reply->user->name }}</strong> - {{ $reply->created_at->diffForHumans() }}</p>
                                    <p class="text-xs">{{ $reply->content }}</p>

                                    @if ($reply->user_id === auth()->id())
                                        <button onclick="deleteComment({{ $reply->id }}, {{ $post->id }})" class="text-red-500 text-xs">Hapus</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($comment->user_id === auth()->id())
                            <button onclick="deleteComment({{ $comment->id }}, {{ $post->id }})" class="text-red-500 text-xs">Hapus</button>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="closeModal({{ $post->id }})" class="btn">Tutup</button>
                </div>
        </div>
    </div>
    @endforeach

    <script>
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json",
                },
            })
            .then(response => response.json())
            .then(data => {
                let likeBtn = document.getElementById(`like-btn-${postId}`);
                let likeCount = document.getElementById(`like-count-${postId}`);
    
                likeCount.textContent = data.likesCount;
                likeBtn.classList.toggle('btn-primary', !data.liked);
                likeBtn.classList.toggle('btn-error', data.liked);
            })
            .catch(error => console.error("Error:", error));
        }
    </script>

    <script>
        function toggleDropdown(postId) {
            document.getElementById("dropdownMenu-" + postId).classList.toggle("hidden");
        }

        document.addEventListener("click", function(event) {
            document.querySelectorAll("[id^='dropdownMenu-']").forEach(menu => {
                if (!event.target.closest(".relative")) {
                    menu.classList.add("hidden");
                }
            });
        }); 

        function openModal(postId) {
        let modal = document.getElementById(`commentModal-${postId}`);
        if (modal) {
            modal.classList.remove("hidden");
        }
        }

        function closeModal(postId) {
        let modal = document.getElementById(`commentModal-${postId}`);
        if (modal) {
            modal.classList.add("hidden");
        }
        }

        function addComment(postId, parentId = null) {
            let form = parentId 
                ? document.getElementById(`reply-form-${parentId}`) 
                : document.getElementById(`comment-form-${postId}`);

            let textarea = form.querySelector("textarea");
            let content = textarea.value.trim();

            if (!content) {
                alert("Komentar tidak boleh kosong!");
                return;
            }

            fetch(`/posts/${postId}/comments`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ content, parent_id: parentId }),
            })
            .then(response => response.json())
            .then(data => {
                if (!data.id) {
                    alert("Gagal menambahkan komentar.");
                    return;
                }

                let commentList = parentId 
                    ? document.getElementById(`replies-${parentId}`) 
                    : document.getElementById(`comment-list-${postId}`);

                let newComment = document.createElement("div");
                newComment.classList.add("border", "p-2", "rounded-lg", "mb-2");
                newComment.setAttribute("id", `comment-${data.id}`);

                let deleteButton = data.user_id == "{{ auth()->id() }}" 
                    ? `<button onclick="deleteComment(${data.id}, ${postId})" class="text-red-500 text-xs">Hapus</button>` 
                    : '';

                newComment.innerHTML = `
                    <p class="text-sm"><strong>${data.user}</strong> - ${data.created_at}</p>
                    <p class="text-sm">${data.content}</p>
                    <button onclick="document.getElementById('reply-form-${data.id}').classList.toggle('hidden')" class="text-blue-500 text-xs">Balas</button>
                    <form id="reply-form-${data.id}" class="hidden mt-2">
                        <textarea class="textarea textarea-bordered w-full text-sm" placeholder="Tulis balasan..." required></textarea>
                        <button type="button" onclick="addComment(${postId}, ${data.id})" class="btn btn-xs btn-primary mt-1">Balas</button>
                    </form>
                    <div id="replies-${data.id}" class="ml-4 border-l-2 pl-2 mt-2"></div>
                    ${deleteButton}
                `;

                commentList.appendChild(newComment);
                textarea.value = ""; 

                let commentCount = document.getElementById(`comment-count-${postId}`);
                if (commentCount) {
                    commentCount.textContent = parseInt(commentCount.textContent) + 1;
                }
            })
            .catch(error => console.error("Error:", error));
        }

        function deleteComment(commentId) {
            fetch(`/comments/${commentId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`comment-${data.comment_id}`).remove();
                } else {
                    alert("Gagal menghapus komentar.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat menghapus komentar.");
            });
        }
    </script>
@endsection