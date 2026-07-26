<x-dashboard-shell title="Dashboard" subtitle="Selamat datang, Admin 👋 Berikut rangkuman laporan sarana prasarana." activeMenu="dashboard">
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="dashboard-card p-5">
                <p class="text-sm text-slate-500">Total Laporan</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($totalLaporan) }}</p>
                <p class="mt-3 text-sm text-slate-500">Semua laporan</p>
            </article>
            <article class="dashboard-card p-5">
                <p class="text-sm text-slate-500">Rusak / Belum</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($belum) }}</p>
                <p class="mt-3 text-sm text-slate-500">Butuh penanganan</p>
            </article>
            <article class="dashboard-card p-5">
                <p class="text-sm text-slate-500">Selesai</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($selesai) }}</p>
                <p class="mt-3 text-sm text-slate-500">Telah selesai</p>
            </article>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
            <section class="dashboard-card p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Grafik Laporan Per Bulan</h3>
                        <p class="mt-1 text-sm text-slate-500">Jumlah laporan setiap bulan untuk tahun {{ $year }}.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="yearInput" type="number" min="2000" max="{{ now()->year }}" value="{{ $year }}" class="w-[100px] rounded-3xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-emerald-500 focus:outline-none" />
                        <button type="button" onclick="(function(){const y=document.getElementById('yearInput').value; if(y) location.href='?year=' + y;})();" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-4 py-2 text-sm font-semibold text-white">Terapkan</button>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-[28px] border border-slate-200 bg-slate-950 p-5">
                    <div class="grid h-56 grid-cols-12 gap-3 items-end">
                        @foreach($monthlyData as $month => $count)
                            @php $height = $maxMonthCount ? round($count / $maxMonthCount * 100) : 0; @endphp
                            <div class="flex flex-col items-center gap-2">
                                <div class="h-40 flex items-end">
                                    <div class="w-6 rounded bg-emerald-500 transition-all duration-300" style="height: {{ $height ?: 6 }}%;"></div>
                                </div>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::create($year, $month, 1)->format('M') }}</span>
                                <span class="text-[10px] text-slate-300">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="space-y-6">
                <article class="dashboard-card p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Laporan Berdasarkan Kategori</h3>
                            <p class="mt-1 text-sm text-slate-500">Distribusi kategori laporan.</p>
                        </div>
                        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-semibold text-emerald-700">{{ $year }}</span>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        @foreach($categories as $categoryName => $count)
                            <div class="rounded-3xl bg-slate-50 p-5 text-center">
                                <p class="text-sm text-slate-500">{{ $categoryName }}</p>
                                <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $categoryPercents[$categoryName] }}%</p>
                                <p class="mt-2 text-xs text-slate-500">{{ number_format($count) }} laporan</p>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </div>

    </div>
</x-dashboard-shell>
