<x-app-layout>
    <div class="px-4 py-6 flex flex-col h-[calc(100vh-180px)]" x-data="chatbot()" x-init="loadHistory()">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">AI Chatbot</h2>
                <p class="text-gray-600 mt-1">Tanya seputar Islam</p>
            </div>
            <button @click="resetChat()" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Reset Sesi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto mb-4 space-y-4 scrollbar-hide" x-ref="chatContainer">
            <div class="flex justify-center">
                <div class="bg-teal-50 rounded-full px-4 py-2 text-sm text-teal-700">
                    Assalamu'alaikum! Ada yang bisa saya bantu?
                </div>
            </div>

            <template x-for="(chat, index) in chats" :key="index">
                <div>
                    <div class="flex justify-end mb-2">
                        <div class="bg-teal-600 text-white rounded-2xl rounded-br-md px-4 py-2.5 max-w-[80%]">
                            <p class="text-sm" x-text="chat.message"></p>
                        </div>
                    </div>
                    <div class="flex justify-start">
                        <div class="bg-white rounded-2xl rounded-bl-md px-4 py-2.5 max-w-[80%] shadow-sm border border-gray-100">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap" x-text="chat.response"></p>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="loading" class="flex justify-start">
                <div class="bg-white rounded-2xl rounded-bl-md px-4 py-3 shadow-sm border border-gray-100">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="chats.length === 0 && !loading" class="mb-4">
            <p class="text-sm text-gray-500 mb-2">Pertanyaan cepat:</p>
            <div class="flex flex-wrap gap-2">
                <button @click="sendQuick('Tata cara wudhu')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Wudhu</button>
                <button @click="sendQuick('Rukun Islam')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Rukun Islam</button>
                <button @click="sendQuick('Sholat tahajud')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Tahajud</button>
                <button @click="sendQuick('Doa sebelum makan')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Doa Makan</button>
                <button @click="sendQuick('Keutamaan sedekah')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Sedekah</button>
                <button @click="sendQuick('Doa masuk masjid')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Doa Masjid</button>
            </div>
        </div>

        <div class="bg-white border-t border-gray-200 -mx-4 px-4 py-3">
            <form @submit.prevent="sendMessage()" class="flex space-x-2">
                <input type="text" x-model="message" :disabled="loading" placeholder="Tanya sesuatu..." class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50">
                <button type="submit" :disabled="!message.trim() || loading" class="bg-teal-600 text-white px-4 py-2.5 rounded-xl hover:bg-teal-700 transition disabled:opacity-50">
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

                async loadHistory() {
                    try {
                        const response = await fetch('/chatbot/history');
                        const data = await response.json();
                        this.chats = data;
                        this.scrollToBottom();
                    } catch (error) {
                        console.error('Error loading history:', error);
                    }
                },

                async sendMessage() {
                    if (!this.message.trim() || this.loading) return;

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
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: userMessage })
                        });

                        const data = await response.json();

                        this.chats[this.chats.length - 1].response = data.response;

                        this.scrollToBottom();
                    } catch (error) {
                        console.error('Error:', error);
                        this.chats[this.chats.length - 1].response = 'Maaf, terjadi kesalahan. Silakan coba lagi.';
                    }

                    this.loading = false;
                },

                async resetChat() {
                    if (!confirm('Reset semua percakapan?')) return;

                    try {
                        await fetch('/chatbot/history', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        this.chats = [];
                    } catch (error) {
                        console.error('Error resetting chat:', error);
                    }
                },

                sendQuick(prompt) {
                    this.message = prompt;
                    this.sendMessage();
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
