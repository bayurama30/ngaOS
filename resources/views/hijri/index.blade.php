<x-app-layout>
    <div class="max-w-[620px] mx-auto px-4 py-6" x-data="hijriCalendar()" x-init="init()">
        {{-- Page Header --}}
        <div class="mb-6 animate-fade-in">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kalender Hijriah</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kalender Islam & Konversi Tanggal</p>
        </div>

        {{-- Today's Hijri Date Card --}}
        <div class="glass-card bg-gradient-to-br from-teal-600 to-teal-700 dark:from-teal-700 dark:to-teal-800 p-5 mb-6 text-white shadow-lg shadow-teal-500/25 animate-slide-up">
            <div class="text-center">
                <p class="text-teal-100 text-sm mb-1">Hari ini</p>
                <p class="font-arabic text-3xl mb-2" x-text="todayHijri || 'Memuat...'"></p>
                <p class="text-teal-100 text-sm" x-text="todayGregorian"></p>
                <p class="text-teal-200 text-xs mt-1" x-text="todayDayName"></p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex space-x-2 mb-6 overflow-x-auto scrollbar-hide pb-2">
            <button @click="activeTab = 'calendar'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition', activeTab === 'calendar' ? 'bg-teal-600 text-white' : 'glass-card text-gray-600 dark:text-gray-300']">Kalender</button>
            <button @click="activeTab = 'convert'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition', activeTab === 'convert' ? 'bg-teal-600 text-white' : 'glass-card text-gray-600 dark:text-gray-300']">Konversi</button>
            <button @click="activeTab = 'holidays'" :class="['px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition', activeTab === 'holidays' ? 'bg-teal-600 text-white' : 'glass-card text-gray-600 dark:text-gray-300']">Hari Besar</button>
        </div>

        {{-- Calendar Tab --}}
        <div x-show="activeTab === 'calendar'" x-cloak>
            {{-- Calendar Type Toggle --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <button @click="calendarType = 'masehi'; loadCalendar()" :class="['px-3 py-1.5 rounded-lg text-sm font-medium transition', calendarType === 'masehi' ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800']">Masehi</button>
                    <button @click="calendarType = 'hijri'; loadCalendar()" :class="['px-3 py-1.5 rounded-lg text-sm font-medium transition', calendarType === 'hijri' ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800']">Hijriah</button>
                </div>
            </div>

            {{-- Month Navigation --}}
            <div class="flex items-center justify-between mb-4">
                <button @click="prevMonth()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="text-center">
                    <h3 class="font-bold text-gray-900 dark:text-white" x-text="calendarTitle"></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="calendarSubtitle"></p>
                </div>
                <button @click="nextMonth()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Day Names Header --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <template x-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']" :key="day">
                    <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2" x-text="day"></div>
                </template>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 gap-1" x-show="!loadingCalendar">
                {{-- Empty cells for first day offset --}}
                <template x-for="i in firstDayOffset" :key="'empty-'+i">
                    <div class="p-2"></div>
                </template>

                {{-- Day cells --}}
                <template x-for="(day, index) in calendarDays" :key="index">
                    <div :class="[
                        'p-2 rounded-xl text-center cursor-pointer transition-all duration-200 relative',
                        day.is_today ? 'bg-teal-600 text-white shadow-md shadow-teal-500/25' : 
                        day.is_ramadhan ? 'bg-gradient-to-b from-amber-100 to-amber-50 dark:from-amber-900/40 dark:to-amber-900/20 border border-amber-200 dark:border-amber-700/50 hover:from-amber-200 hover:to-amber-100 dark:hover:from-amber-900/50 dark:hover:to-amber-900/30' :
                        day.is_friday ? 'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30' :
                        'hover:bg-gray-100 dark:hover:bg-gray-800'
                    ]">
                        <p :class="['text-sm font-medium', day.is_today ? 'text-white' : day.is_ramadhan ? 'text-amber-800 dark:text-amber-200' : 'text-gray-900 dark:text-white']" x-text="day.day"></p>
                        <p :class="['text-xs', day.is_today ? 'text-teal-100' : day.is_ramadhan ? 'text-amber-600 dark:text-amber-400 font-medium' : 'text-gray-400 dark:text-gray-500']" x-text="calendarType === 'masehi' ? day.hijri_day : day.gregorian_day"></p>
                        {{-- Ramadhan moon icon --}}
                        <div x-show="day.is_ramadhan && !day.is_today" class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center">
                            <span class="text-xs">🌙</span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Loading --}}
            <div x-show="loadingCalendar" class="text-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 dark:border-teal-400 mx-auto"></div>
                <p class="text-gray-500 dark:text-gray-400 mt-3">Memuat kalender...</p>
            </div>

            {{-- Legend --}}
            <div class="flex items-center justify-center space-x-4 mt-4 text-xs text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-teal-600 rounded-full"></div>
                    <span>Hari ini</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-gradient-to-b from-amber-100 to-amber-50 border border-amber-200 rounded-full"></div>
                    <span>Ramadhan 🌙</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-green-200 dark:bg-green-800 rounded-full"></div>
                    <span>Jumat</span>
                </div>
            </div>
        </div>

        {{-- Convert Tab --}}
        <div x-show="activeTab === 'convert'" x-cloak>
            <div class="glass-card p-5 animate-slide-up">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Konversi Tanggal</h3>
                
                {{-- Conversion Type --}}
                <div class="flex space-x-2 mb-4">
                    <button @click="convertType = 'masehi-to-hijri'" :class="['flex-1 px-3 py-2 rounded-xl text-sm font-medium transition', convertType === 'masehi-to-hijri' ? 'bg-teal-600 text-white' : 'glass-card text-gray-600 dark:text-gray-300']">Masehi → Hijriah</button>
                    <button @click="convertType = 'hijri-to-masehi'" :class="['flex-1 px-3 py-2 rounded-xl text-sm font-medium transition', convertType === 'hijri-to-masehi' ? 'bg-teal-600 text-white' : 'glass-card text-gray-600 dark:text-gray-300']">Hijriah → Masehi</button>
                </div>

                {{-- Input Fields --}}
                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Hari</label>
                            <select x-model="convertDay" class="input text-sm">
                                <template x-for="d in 31" :key="d">
                                    <option :value="d" x-text="d"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Bulan</label>
                            <select x-model="convertMonth" class="input text-sm">
                                <template x-for="(m, idx) in (convertType === 'masehi-to-hijri' ? masehiMonths : hijriMonths)" :key="idx">
                                    <option :value="idx + 1" x-text="m"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tahun</label>
                            <input type="number" x-model="convertYear" class="input text-sm" min="1" max="9999">
                        </div>
                    </div>
                </div>

                <button @click="doConvert()" class="w-full btn-primary py-3" :disabled="loadingConvert">
                    <span x-show="!loadingConvert">Konversi</span>
                    <span x-show="loadingConvert">Mengkonversi...</span>
                </button>

                {{-- Result --}}
                <div x-show="convertResult" x-cloak class="mt-4 glass-card p-4 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Hasil:</p>
                    <p class="text-lg font-bold text-teal-700 dark:text-teal-300" x-text="convertResult?.formatted"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-show="convertResult?.day_name" x-text="convertResult?.day_name"></p>
                </div>
            </div>
        </div>

        {{-- Holidays Tab --}}
        <div x-show="activeTab === 'holidays'" x-cloak>
            <div class="space-y-3 animate-slide-up">
                <template x-for="(holiday, index) in holidays" :key="index">
                    <div class="card-hover">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-xl" x-text="holiday.icon"></span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-white" x-text="holiday.name"></h4>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium" x-text="holiday.hijri"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="holiday.description"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function hijriCalendar() {
            return {
                activeTab: 'calendar',
                calendarType: 'masehi',
                currentMonth: new Date().getMonth() + 1,
                currentYear: new Date().getFullYear(),
                currentHijriMonth: 1,
                currentHijriYear: 1447,
                calendarDays: [],
                calendarTitle: '',
                calendarSubtitle: '',
                firstDayOffset: 0,
                loadingCalendar: true,
                todayHijri: '',
                todayGregorian: '',
                todayDayName: '',

                convertType: 'masehi-to-hijri',
                convertDay: new Date().getDate(),
                convertMonth: new Date().getMonth() + 1,
                convertYear: new Date().getFullYear(),
                convertResult: null,
                loadingConvert: false,

                holidays: [],

                masehiMonths: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                hijriMonths: ['Muharram', 'Safar', 'Rabiul Awal', 'Rabiul Akhir', 'Jumadil Awal', 'Jumadil Akhir', 'Rajab', 'Sya\'ban', 'Ramadhan', 'Syawal', 'Dzulqa\'dah', 'Dzulhijjah'],

                async init() {
                    await this.loadToday();
                    await this.loadCalendar();
                    await this.loadHolidays();
                },

                async loadToday() {
                    try {
                        const res = await fetch('/api/hijri/today');
                        const data = await res.json();
                        if (data?.hijr) {
                            this.todayHijri = data.hijr.today || '';
                            this.todayDayName = data.hijr.dayName || '';
                        }
                        if (data?.date) {
                            this.todayGregorian = data.date.full || '';
                        }
                    } catch (e) {
                        console.error('Error loading today:', e);
                    }
                },

                async loadCalendar() {
                    this.loadingCalendar = true;
                    try {
                        const month = this.calendarType === 'hijri' ? this.currentHijriMonth : this.currentMonth;
                        const year = this.calendarType === 'hijri' ? this.currentHijriYear : this.currentYear;

                        const res = await fetch(`/api/hijri/calendar?month=${month}&year=${year}&type=${this.calendarType}`);
                        const data = await res.json();

                        if (data?.success) {
                            this.calendarDays = data.days || [];
                            this.calendarTitle = `${data.month_name} ${data.year}`;
                            
                            if (this.calendarType === 'masehi') {
                                const firstDay = new Date(year, month - 1, 1).getDay();
                                this.firstDayOffset = firstDay === 0 ? 6 : firstDay - 1;
                                const hijriFirst = this.calendarDays[0];
                                const hijriLast = this.calendarDays[this.calendarDays.length - 1];
                                this.calendarSubtitle = `${hijriFirst?.hijri_month_name || ''} - ${hijriLast?.hijri_month_name || ''} ${hijriFirst?.hijri_year || ''} H`;
                            } else {
                                const firstGregorian = this.calendarDays[0];
                                const firstDay = new Date(firstGregorian?.gregorian_year, firstGregorian?.gregorian_month - 1, firstGregorian?.gregorian_day).getDay();
                                this.firstDayOffset = firstDay === 0 ? 6 : firstDay - 1;
                                this.calendarSubtitle = `${this.masehiMonths[(firstGregorian?.gregorian_month || 1) - 1]} ${firstGregorian?.gregorian_year || ''} M`;
                            }
                        }
                    } catch (e) {
                        console.error('Error loading calendar:', e);
                    }
                    this.loadingCalendar = false;
                },

                prevMonth() {
                    if (this.calendarType === 'hijri') {
                        this.currentHijriMonth--;
                        if (this.currentHijriMonth < 1) {
                            this.currentHijriMonth = 12;
                            this.currentHijriYear--;
                        }
                    } else {
                        this.currentMonth--;
                        if (this.currentMonth < 1) {
                            this.currentMonth = 12;
                            this.currentYear--;
                        }
                    }
                    this.loadCalendar();
                },

                nextMonth() {
                    if (this.calendarType === 'hijri') {
                        this.currentHijriMonth++;
                        if (this.currentHijriMonth > 12) {
                            this.currentHijriMonth = 1;
                            this.currentHijriYear++;
                        }
                    } else {
                        this.currentMonth++;
                        if (this.currentMonth > 12) {
                            this.currentMonth = 1;
                            this.currentYear++;
                        }
                    }
                    this.loadCalendar();
                },

                async doConvert() {
                    this.loadingConvert = true;
                    this.convertResult = null;
                    try {
                        const res = await fetch(`/api/hijri/convert?type=${this.convertType}&day=${this.convertDay}&month=${this.convertMonth}&year=${this.convertYear}`);
                        const data = await res.json();
                        if (data?.success) {
                            this.convertResult = data.result;
                        }
                    } catch (e) {
                        console.error('Error converting:', e);
                    }
                    this.loadingConvert = false;
                },

                async loadHolidays() {
                    try {
                        const res = await fetch('/api/hijri/holidays');
                        const data = await res.json();
                        this.holidays = Array.isArray(data) ? data : [];
                    } catch (e) {
                        console.error('Error loading holidays:', e);
                    }
                }
            };
        }
    </script>
</x-app-layout>
