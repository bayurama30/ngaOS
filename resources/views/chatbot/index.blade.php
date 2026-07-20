<x-app-layout>
    <style>
        .chat-content strong { font-weight: 700; }
        .chat-content em { font-style: italic; }
        .chat-content code { font-family: monospace; }
        .chat-content blockquote { margin: 4px 0; }
        .chat-content h1, .chat-content h2, .chat-content h3 { line-height: 1.4; }
        .chat-content br + br { display: block; content: ""; margin-top: 4px; }
    </style>

    <div class="max-w-[620px] mx-auto px-4 py-6 flex flex-col h-[calc(100vh-180px)]" x-data="chatbot()" x-cloak>
        <div class="flex items-center justify-between mb-4 animate-fade-in">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">AI Chatbot</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Tanya seputar Islam</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="badge text-xs" :class="remaining <= 3 ? 'badge-red' : 'badge-teal'" x-text="`${remaining}/10 hari ini`"></span>
                <button @click="resetChat()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Hapus Semua Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="remaining <= 0" x-cloak class="glass-card p-4 mb-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
            <p class="text-amber-700 dark:text-amber-300 text-sm text-center">Anda telah mencapai batas chat hari ini. Silakan coba lagi besok.</p>
        </div>

        <div class="flex-1 overflow-y-auto mb-4 space-y-4 scrollbar-hide" x-ref="chatContainer">
            <div class="flex justify-center" x-show="chats.length === 0 && !loading">
                <div class="glass-card px-4 py-2 text-sm text-teal-700 dark:text-teal-300">
                    Assalamu'alaikum! Ada yang bisa saya bantu?
                </div>
            </div>

            <template x-for="(chat, index) in chats" :key="index">
                <div class="animate-fade-in">
                    <div class="flex justify-end mb-2">
                        <div class="bg-teal-600 dark:bg-teal-500 text-white rounded-2xl rounded-br-md px-4 py-2.5 max-w-[80%]">
                            <p class="text-sm" x-text="chat.message"></p>
                        </div>
                    </div>
                    <div class="flex justify-start" x-show="chat.response">
                        <div class="glass-card rounded-2xl rounded-bl-md px-4 py-2.5 max-w-[80%]">
                            <div class="text-sm text-gray-700 dark:text-gray-300 chat-content" x-html="formatMessage(chat.response)"></div>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="loading" class="flex justify-start">
                <div class="glass-card rounded-2xl rounded-bl-md px-4 py-3">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="chats.length === 0 && !loading" x-cloak class="mb-4">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Pertanyaan cepat:</p>
            <div class="flex flex-wrap gap-2">
                <button @click="sendQuick('Tata cara wudhu')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Wudhu</button>
                <button @click="sendQuick('Rukun Islam')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Rukun Islam</button>
                <button @click="sendQuick('Sholat tahajud')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Tahajud</button>
                <button @click="sendQuick('Doa sebelum makan')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Doa Makan</button>
                <button @click="sendQuick('Keutamaan sedekah')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Sedekah</button>
                <button @click="sendQuick('Doa masuk masjid')" class="glass-card px-3 py-1.5 rounded-full text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Doa Masjid</button>
            </div>
        </div>

        <div class="glass-card border-t border-gray-200 dark:border-gray-700 -mx-4 px-4 py-3 rounded-t-none">
            <div x-show="error" x-cloak class="glass-card p-3 mb-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <p class="text-red-600 dark:text-red-400 text-sm" x-text="error"></p>
            </div>
            <form @submit.prevent="sendMessage()" class="flex space-x-2">
                <div class="flex-1 relative">
                    <input type="text" x-model="message" :disabled="loading || remaining <= 0" @keydown.enter.prevent="sendMessage()" placeholder="Tanya sesuatu..." maxlength="500" class="input pr-16 disabled:opacity-50">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs" :class="message.length > 450 ? 'text-red-500' : 'text-gray-400 dark:text-gray-500'" x-text="`${message.length}/500`"></span>
                </div>
                <button type="submit" :disabled="!message.trim() || loading || remaining <= 0" class="btn-primary px-4 disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        function chatbot() {
            return {
                chats: [],
                message: '',
                loading: false,
                error: '',
                remaining: 10,

                init() {
                    this.$nextTick(() => this.loadHistory());
                },

                async loadHistory() {
                    try {
                        const timestamp = new Date().getTime();
                        const response = await fetch(`/chatbot/history?t=${timestamp}`, {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Cache-Control': 'no-cache'
                            }
                        });

                        if (response.ok) {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                const data = await response.json();
                                if (data && data.chats && Array.isArray(data.chats)) {
                                    this.chats = data.chats;
                                    this.remaining = data.remaining ?? 10;
                                    this.$nextTick(() => this.scrollToBottom());
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error loading history:', error);
                    }
                },

                async sendMessage() {
                    if (!this.message.trim() || this.loading || this.remaining <= 0) return;

                    this.error = '';

                    if (this.message.length > 500) {
                        this.error = 'Pesan maksimal 500 karakter.';
                        return;
                    }

                    const userMessage = this.message;
                    this.message = '';
                    this.loading = true;

                    this.chats.push({
                        message: userMessage,
                        response: ''
                    });

                    this.scrollToBottom();

                    try {
                        const response = await fetch('/chatbot/chat', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ message: userMessage })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.error) {
                            this.error = data.error;
                            this.chats.pop();
                            if (response.status === 429) {
                                this.remaining = 0;
                            }
                        } else {
                            this.chats[this.chats.length - 1].response = data.response;
                            this.remaining = data.remaining ?? this.remaining;
                        }

                        this.scrollToBottom();
                    } catch (error) {
                        console.error('Error:', error);
                        this.chats[this.chats.length - 1].response = 'Maaf, terjadi kesalahan. Silakan coba lagi.';
                    }

                    this.loading = false;
                },

                async resetChat() {
                    if (!confirm('Hapus semua percakapan?')) return;

                    try {
                        const response = await fetch('/chatbot/history', {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.chats = [];
                            this.remaining = data.remaining ?? 10;
                            this.error = '';
                        }
                    } catch (error) {
                        console.error('Error resetting chat:', error);
                    }
                },

                sendQuick(prompt) {
                    this.message = prompt;
                    this.sendMessage();
                },

                formatMessage(text) {
                    if (!text) return '';

                    text = text.replace(/</g, '&lt;').replace(/>/g, '&gt;');

                    text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    text = text.replace(/`(.*?)`/g, '<code class="bg-gray-100 dark:bg-gray-800 px-1 rounded text-xs">$1</code>');

                    text = text.replace(/^### (.*$)/gm, '<h3 class="font-bold text-base mt-2 mb-1">$1</h3>');
                    text = text.replace(/^## (.*$)/gm, '<h2 class="font-bold text-lg mt-2 mb-1">$1</h2>');
                    text = text.replace(/^# (.*$)/gm, '<h1 class="font-bold text-xl mt-2 mb-1">$1</h1>');

                    text = text.replace(/^> (.*$)/gm, '<blockquote class="border-l-2 border-gray-300 dark:border-gray-600 pl-2 italic text-gray-600 dark:text-gray-400 my-1">$1</blockquote>');

                    text = text.replace(/^\d+\. (.*$)/gm, '<div class="ml-4">$&</div>');
                    text = text.replace(/^- (.*$)/gm, '<div class="ml-4">• $1</div>');

                    text = text.replace(/\n/g, '<br>');

                    return text;
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                }
            };
        }
    </script>
</x-app-layout>
