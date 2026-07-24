<x-app-layout>
    <div class="px-4 py-6" x-data="postDetail({{ $post->id }})">
        <div class="mb-4">
            <a href="{{ route('forum.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="card p-5 mb-4">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center mr-3">
                    <span class="text-teal-600 dark:text-teal-400 font-bold text-lg">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>
                <span class="ml-auto badge-teal badge">{{ $post->category }}</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $post->title }}</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $post->body }}</p>
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}" alt="" class="mt-4 rounded-xl w-full max-h-96 object-cover">
            @endif
            <div class="flex items-center mt-4 pt-4 border-t border-white/20 dark:border-white/10 space-x-4">
                <button @click="toggleLike()" :class="['flex items-center space-x-1', liked ? 'text-red-500' : 'text-gray-500 dark:text-gray-400']">
                    <svg class="w-5 h-5" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span x-text="likesCount"></span>
                </button>
                <span class="text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>{{ $post->comments_count }}</span>
                </span>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Komentar ({{ $post->comments_count }})</h3>
            <form action="{{ route('forum.comment', $post) }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="body" required placeholder="Tulis komentar..." class="flex-1 glass-input px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500/50 dark:focus:ring-teal-400/50 transition-all duration-200">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="space-y-3">
            @foreach($post->comments as $comment)
                <div class="card p-4">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mr-2">
                            <span class="text-gray-600 dark:text-gray-300 text-sm font-bold">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">{{ $comment->user->name }}</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $comment->body }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function postDetail(postId) {
            return {
                liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                likesCount: {{ $post->likes_count }},

                async toggleLike() {
                    try {
                        const response = await fetch(`/forum/${postId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.liked = data.liked;
                        this.likesCount = data.likes_count;
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            };
        }
    </script>
</x-app-layout>
