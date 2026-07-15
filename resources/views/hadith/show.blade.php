<x-app-layout>
    <div class="px-4 py-6" x-data="hadithShow({{ $hadithId }})" x-init="loadHadith()">
        <div class="mb-4">
            <a href="{{ route('hadith.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat hadis...</p>
        </div>

        <div x-show="!loading && hadith" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-teal-600">Hadis #<span x-text="hadithId"></span></span>
                <span :class="[hadith?.grade?.includes('Shahih') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700', 'px-3 py-1 rounded-full text-xs font-medium']" x-text="hadith?.grade || ''"></span>
            </div>

            <div class="mb-6">
                <p class="text-2xl text-right leading-loose text-gray-800 mb-4" style="font-family: 'LPMQ IsepMisbah', serif" x-text="hadith?.text?.ar || ''"></p>
                <p class="text-gray-700 leading-relaxed" x-text="hadith?.text?.id || ''"></p>
            </div>

            <div class="border-t border-gray-100 pt-4 space-y-2">
                <div x-show="hadith?.takhrij" class="flex items-start">
                    <span class="text-sm text-gray-500 w-24">Sumber:</span>
                    <span class="text-sm text-gray-700" x-text="hadith?.takhrij || ''"></span>
                </div>
                <div x-show="hadith?.hikmah" class="flex items-start">
                    <span class="text-sm text-gray-500 w-24">Hikmah:</span>
                    <span class="text-sm text-gray-700" x-text="hadith?.hikmah || ''"></span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                <button @click="prevHadis()" :disabled="!hadith?.prev" class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-gray-700 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Sebelumnya
                </button>
                <button @click="nextHadis()" :disabled="!hadith?.next" class="flex items-center gap-2 px-4 py-2 bg-teal-600 rounded-lg text-white disabled:opacity-50">
                    Selanjutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        function hadithShow(id) {
            return {
                hadithId: id,
                hadith: null,
                loading: true,

                async loadHadith() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/api/muslim/hadis/${this.hadithId}`);
                        this.hadith = await response.json();
                    } catch (error) {
                        console.error('Error loading hadith:', error);
                    }
                    this.loading = false;
                },

                async prevHadis() {
                    if (this.hadith?.prev) {
                        this.hadithId = this.hadith.prev;
                        await this.loadHadith();
                        window.scrollTo(0, 0);
                    }
                },

                async nextHadis() {
                    if (this.hadith?.next) {
                        this.hadithId = this.hadith.next;
                        await this.loadHadith();
                        window.scrollTo(0, 0);
                    }
                }
            };
        }
    </script>
</x-app-layout>
