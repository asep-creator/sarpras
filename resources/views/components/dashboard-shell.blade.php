@props(['title' => 'Dashboard', 'subtitle' => '', 'activeMenu' => 'dashboard'])

<x-app-layout>
    <div class="min-h-screen bg-slate-50" x-data="{ open: false }">
        <div class="flex min-h-screen">
            <aside :class="{ 'translate-x-0': open, '-translate-x-full': !open }" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-emerald-950 p-6 text-white shadow-2xl transition-transform duration-200 lg:w-72 lg:static lg:block lg:translate-x-0">
                <div class="flex items-center justify-between lg:justify-start">
                    <div>
                        <p class="text-sm text-emerald-100">SARPRAS</p>
                        <h3 class="text-lg font-semibold">Menu Utama</h3>
                    </div>
                    <button class="rounded-full p-2 text-emerald-100 hover:bg-white/10 lg:hidden" @click="open = false">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <nav class="mt-8 space-y-2 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'dashboard' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span>▣</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('data.sipil') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'sipil' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span>▤</span>
                        <span>Data SIPIL</span>
                    </a>
                    <a href="{{ route('data.electrical') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'electrical' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span>⚡</span>
                        <span>Data ELECTRICAL</span>
                    </a>
                    <a href="{{ route('data.plumbing') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'plumbing' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span>🔧</span>
                        <span>Data PLUMBING</span>
                    </a>
                    {{-- Removed Grafik & Statistik and Laporan menu per request --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('pengguna.index') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'pengguna' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                            <span>👤</span>
                            <span>Pengguna</span>
                        </a>
                    @endif
                    <a href="{{ route('pengaturan') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $activeMenu === 'pengaturan' ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span>⚙</span>
                        <span>Pengaturan</span>
                    </a>
                </nav>
            </aside>

            <div class="flex-1">
                <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button class="rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm lg:hidden" @click="open = !open">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $title }}</h2>
                            @if($subtitle)
                                <p class="text-sm text-slate-500">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 z-30 bg-slate-950/40 lg:hidden"></div>
    </div>
</x-app-layout>
