@php
    $backRoute = route('data.' . $category);
@endphp

<x-dashboard-shell title="Review Laporan {{ $categoryLabel }}" subtitle="Review laporan yang sudah dikerjakan" activeMenu="{{ $category }}">
    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Review Laporan</h3>
                <p class="mt-1 text-sm text-slate-500">Hanya riwayat terpilih yang ditampilkan agar review tidak menumpuk dengan riwayat lain.</p>
            </div>
            <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali ke {{ $categoryLabel }}</a>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-[32px] border border-slate-200 bg-slate-50 p-6">
                <h4 class="text-sm font-semibold text-slate-900">Detail Laporan</h4>
                <dl class="mt-4 space-y-4 text-sm text-slate-700">
                    <div>
                        <dt class="font-semibold">Tanggal Laporan</dt>
                        <dd>{{ $laporan->tanggal?->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Lokasi / Ruangan</dt>
                        <dd>{{ $laporan->lokasi }} / {{ $laporan->ruangan }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Kondisi</dt>
                        <dd>{{ $laporan->kondisi }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Deskripsi</dt>
                        <dd>{{ $laporan->deskripsi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Foto Kerusakan</dt>
                        <dd class="mt-3">
                            @if(!empty($laporan->foto_paths) && is_array($laporan->foto_paths))
                                <div x-data="{ activePhoto: null }" class="grid gap-3 sm:grid-cols-3">
                                    @foreach($laporan->foto_paths as $foto)
                                        @php
                                            $fotoPath = trim($foto, '/');
                                            $fotoUrl = \Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://', 'data:']) ? $fotoPath : asset('storage/' . $fotoPath);
                                        @endphp
                                        <button type="button" @click="activePhoto = '{{ $fotoUrl }}'" class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-0 text-left shadow-sm transition hover:shadow-md aspect-square">
                                            <img src="{{ $fotoUrl }}" alt="Foto kerusakan" class="h-full w-full object-cover" />
                                        </button>
                                    @endforeach

                                    <div x-show="activePhoto" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4" @click.self="activePhoto = null">
                                        <div class="relative max-h-[90vh] w-full max-w-4xl overflow-hidden rounded-3xl bg-slate-900 shadow-2xl">
                                            <button type="button" @click="activePhoto = null" class="absolute right-4 top-4 z-20 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-sm">×</button>
                                            <img :src="activePhoto" class="h-[85vh] w-full object-contain bg-slate-900" alt="Foto perbesar" />
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-500">Tidak ada foto terlampir.</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
                <h4 class="text-sm font-semibold text-slate-900">Detail Pengerjaan</h4>
                <dl class="mt-4 space-y-4 text-sm text-slate-700">
                    <div>
                        <dt class="font-semibold">Tanggal Pengerjaan</dt>
                        <dd>{{ $laporan->tanggal_pengerjaan?->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Nama Tukang</dt>
                        <dd>{{ $laporan->nama_tukang ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Estimasi</dt>
                        <dd>{{ $laporan->estimasi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Catatan Pengerjaan</dt>
                        <dd>{{ $laporan->detail_pengerjaan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Status</dt>
                        @php
                            $reviewStatus = $laporan->status === 'baru' ? 'Belum' : 'Selesai';
                            $reviewClass = $laporan->status === 'baru' ? 'text-rose-600' : 'text-emerald-700';
                        @endphp
                        <dd class="{{ $reviewClass }}">{{ $reviewStatus }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
</x-dashboard-shell>
