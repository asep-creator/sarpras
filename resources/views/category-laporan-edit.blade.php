@php
    $backRoute = route('data.' . $category);
    $submitRoute = route('data.' . $category . '.update', $laporan);
@endphp

<x-dashboard-shell title="Edit Laporan {{ $categoryLabel }}" subtitle="Ubah data laporan yang sudah ada" activeMenu="{{ $category }}">
    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Edit Laporan</h3>
                <p class="mt-1 text-sm text-slate-500">Perbarui data laporan sesuai kebutuhan.</p>
            </div>
            <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali ke {{ $categoryLabel }}</a>
        </div>

        <form action="{{ $submitRoute }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('success') }}</div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    Tanggal Laporan
                    <input type="date" name="tanggal" value="{{ old('tanggal', optional($laporan->tanggal)->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required />
                </label>
                <label class="block text-sm text-slate-700">
                    Lokasi
                    <input type="text" name="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" placeholder="Masukkan lokasi" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required />
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    Ruangan
                    <input type="text" name="ruangan" value="{{ old('ruangan', $laporan->ruangan) }}" placeholder="Masukkan ruangan" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required />
                </label>
                <label class="block text-sm text-slate-700">
                    Kondisi
                    <select name="kondisi" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                        <option value="Middle" {{ old('kondisi', $laporan->kondisi) === 'Middle' ? 'selected' : '' }}>Middle</option>
                        <option value="Urgent" {{ old('kondisi', $laporan->kondisi) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </label>
            </div>

            <label class="block text-sm text-slate-700">
                Deskripsi Kerusakan
                <textarea name="deskripsi" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" placeholder="Deskripsi kerusakan">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    Tanggal Pengerjaan
                    <input type="date" name="tanggal_pengerjaan" value="{{ old('tanggal_pengerjaan', optional($laporan->tanggal_pengerjaan)->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" />
                </label>
                <label class="block text-sm text-slate-700">
                    Nama Tukang
                    <input type="text" name="nama_tukang" value="{{ old('nama_tukang', $laporan->nama_tukang) }}" placeholder="Nama tukang" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" />
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    Estimasi
                    <input type="text" name="estimasi" value="{{ old('estimasi', $laporan->estimasi) }}" placeholder="Estimasi" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" />
                </label>
                <label class="block text-sm text-slate-700">
                    Detail Pengerjaan
                    <textarea name="detail_pengerjaan" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" placeholder="Detail pengerjaan">{{ old('detail_pengerjaan', $laporan->detail_pengerjaan) }}</textarea>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-6 py-3 text-sm text-slate-700">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-6 py-3 text-sm font-semibold text-white">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-dashboard-shell>
