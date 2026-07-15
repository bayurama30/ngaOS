<x-app-layout>
    <div class="px-4 py-6" x-data="forumIndex()">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Forum</h2>
                <p class="text-gray-600 mt-1">Diskusi Islami</p>
            </div>
            <button @click="showCreateModal = true" class="bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-teal-700 transition">
                + Posting
            </button>
        </div>

        <div class="flex space-x-2 mb-4 overflow-x-auto scrollbar-hide pb-2">
            <button @click="category = ''" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === '' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Semua</button>
            <button @click="category = 'umum'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === 'umum' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Umum</button>
            <button @click="category = 'tafsir'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === 'tafsir' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Tafsir</button>
            <button @click="category = 'hadis'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === 'hadis' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Hadis</button>
            <button @click="category = 'doa'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === 'doa' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Doa</button>
            <button @click="category = 'fiqih'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap', category === 'fiqih' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600']">Fiqih</button>
        </div>

        @php
            $posts = \App\Models\Post::with('user')->latest()->paginate(10);
        @endphp

        <div class="space-y-3">
            @forelse($posts as $post)
                <a href="{{ route('forum.show', $post) }}" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-teal-600 font-bold">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $post->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="ml-auto bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full text-xs">{{ $post->category }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">{{ $post->title }}</h3>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($post->body, 150) }}</p>
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="" class="mt-3 rounded-lg w-full h-48 object-cover">
                    @endif
                    <div class="flex items-center mt-3 text-sm text-gray-500 space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ $post->likes_count }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->comments_count }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada postingan</p>
                    <button @click="showCreateModal = true" class="mt-3 text-teal-600 font-medium">Buat Postingan Pertama</button>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

        <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end justify-center p-4">
            <div @click.away="showCreateModal = false" class="bg-white rounded-t-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Buat Postingan</h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="umum">Umum</option>
                                <option value="tafsir">Tafsir</option>
                                <option value="hadis">Hadis</option>
                                <option value="doa">Doa</option>
                                <option value="fiqih">Fiqih</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="title" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Judul postingan...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
                            <textarea name="body" rows="4" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Tulis sesuatu..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (opsional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition">Posting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function forumIndex() {
            return {
                showCreateModal: false,
                category: ''
            };
        }
    </script>
</x-app-layout>
