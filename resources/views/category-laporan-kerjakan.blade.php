@php
    $backRoute = route('data.' . $category);
    $submitRoute = route('data.' . $category . '.work.store', $laporan);
@endphp

<x-dashboard-shell title="Kerjakan Laporan {{ $categoryLabel }}" subtitle="Kerjakan laporan terpilih" activeMenu="{{ $category }}">
    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Kerjakan Laporan</h3>
                <p class="mt-1 text-sm text-slate-500">Hanya riwayat yang dipilih akan ditampilkan di halaman ini.</p>
            </div>
            <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali ke {{ $categoryLabel }}</a>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <section x-data="{ activePhoto: null }" class="rounded-[32px] border border-slate-200 bg-slate-50 p-6">
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
                                <div class="grid gap-3 sm:grid-cols-3">
                                    @foreach($laporan->foto_paths as $foto)
                                        @php
                                            $fotoPath = trim($foto, '/');
                                            $fotoUrl = \Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://', 'data:']) ? $fotoPath : asset('storage/' . $fotoPath);
                                        @endphp
                                        <button type="button" @click="activePhoto = '{{ $fotoUrl }}'" class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-0 text-left shadow-sm transition hover:shadow-md aspect-square">
                                            <img src="{{ $fotoUrl }}" alt="Foto kerusakan" class="h-full w-full object-cover" />
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-500">Tidak ada foto terlampir.</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div x-show="activePhoto" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4" @click.self="activePhoto = null">
                    <div class="relative max-h-[90vh] w-full max-w-4xl overflow-hidden rounded-3xl bg-slate-900 shadow-2xl">
                        <button type="button" @click="activePhoto = null" class="absolute right-4 top-4 z-20 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-sm">×</button>
                        <img :src="activePhoto" class="h-[85vh] w-full object-contain bg-slate-900" alt="Foto perbesar" />
                    </div>
                </div>
            </section>

            <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
                <h4 class="text-sm font-semibold text-slate-900">Form Kerjakan</h4>

                @if(session('success'))
                    <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('success') }}</div>
                @endif

                <form action="{{ $submitRoute }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <label class="block text-sm text-slate-700">
                        Tanggal Pengerjaan
                        <input type="date" name="tanggal_pengerjaan" value="{{ old('tanggal_pengerjaan', optional($laporan->tanggal_pengerjaan)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required />
                    </label>

                    <label class="block text-sm text-slate-700">
                        Nama Tukang
                        <input type="text" name="nama_tukang" value="{{ old('nama_tukang', $laporan->nama_tukang) }}" placeholder="Masukkan nama tukang" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required />
                    </label>

                    <label class="block text-sm text-slate-700">
                        Estimasi
                        <input type="text" name="estimasi" value="{{ old('estimasi', $laporan->estimasi) }}" placeholder="Estimasi Harga" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" />
                    </label>

                    <label class="block text-sm text-slate-700">
                        Detail Pengerjaan
                        <textarea name="detail_pengerjaan" rows="5" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" placeholder="Tuliskan tindakan yang diambil...">{{ old('detail_pengerjaan', $laporan->detail_pengerjaan) }}</textarea>
                    </label>

                    <div class="flex justify-end gap-3">
                        <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-6 py-3 text-sm text-slate-700">Batal</a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-6 py-3 text-sm font-semibold text-white">Simpan Kerjaan</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-dashboard-shell>
