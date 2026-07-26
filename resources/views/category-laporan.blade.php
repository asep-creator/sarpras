@php
    $storeRoute = route('data.' . $category . '.store');
    $pageRoute = route('data.' . $category);
@endphp

<x-dashboard-shell title="Data {{ $categoryLabel }}" subtitle="Tambah laporan & riwayat per bulan" activeMenu="{{ $category }}">
    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Tambah Laporan {{ $categoryLabel }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Form input otomatis menggunakan kategori ini.</p>
                </div>
                <span class="rounded-full bg-emerald-500/10 px-3 py-2 text-sm font-semibold text-emerald-700">Kategori: {{ $categoryLabel }}</span>
            </div>

            <form x-data="laporanForm('{{ $storeRoute }}')" @submit.prevent="submit" class="mt-6 space-y-5">
                <div class="grid gap-4 sm:grid-cols-1">
                    <label class="block text-sm text-slate-700">
                        Tanggal Laporan
                        <input x-model="form.tanggal" type="date" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:outline-none" />
                    </label>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-sm text-slate-700">
                        Lokasi
                        <input x-model="form.lokasi" type="text" placeholder="Masukkan lokasi" class="mt-2 w-full rounded-3xl border border-slate-200 px-4 py-3 text-sm shadow-sm" />
                    </label>
                    <label class="block text-sm text-slate-700">
                        Ruangan
                        <input x-model="form.ruangan" type="text" placeholder="Masukkan ruangan" class="mt-2 w-full rounded-3xl border border-slate-200 px-4 py-3 text-sm shadow-sm" />
                    </label>
                </div>

                <div>
                    <p class="text-sm text-slate-700">Kondisi</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm" :class="form.kondisi==='Middle' ? 'bg-yellow-100 ring-2 ring-yellow-500' : 'bg-white'">
                            <input type="radio" x-model="form.kondisi" value="Middle" name="kondisi">
                            Middle
                        </label>
                        <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm" :class="form.kondisi==='Urgent' ? 'bg-red-100 ring-2 ring-red-500' : 'bg-white'">
                            <input type="radio" x-model="form.kondisi" value="Urgent" name="kondisi">
                            Urgent
                        </label>
                    </div>
                </div>

                <label class="block text-sm text-slate-700">
                    Deskripsi Kerusakan
                    <textarea x-model="form.deskripsi" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 px-4 py-3 text-sm shadow-sm" placeholder="Dinding retak dan cat mengelupas di beberapa bagian."></textarea>
                </label>

                <div>
                    <p class="text-sm text-slate-700">Foto Kerusakan</p>
                    <input x-ref="fileInput" type="file" name="foto[]" multiple accept="image/*" @change="handleFiles($event)" class="mt-3" />
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <template x-for="(img, idx) in images" :key="idx">
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200">
                            <img :src="img" class="h-28 w-full object-cover" />
                            <button type="button" @click="removeImage(idx)" class="absolute right-2 top-2 rounded-full bg-white/90 p-1 text-sm">✕</button>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="reset" @click="resetForm" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-6 py-3 text-sm text-slate-700">Reset</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-6 py-3 text-sm font-semibold text-white">Simpan Laporan</button>
                </div>
            </form>
        </section>
    </div>

    <div class="mt-6 rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Laporan {{ $categoryLabel }}</h3>
                <p class="mt-1 text-sm text-slate-500">Menampilkan laporan untuk bulan {{ $months[$selectedMonth] }} {{ $selectedYear }}.</p>
            </div>
            <div class="rounded-full bg-slate-100 px-3 py-2 text-sm text-slate-700">
                Total: {{ $laporans->count() }} laporan
            </div>
        </div>

        <form method="GET" action="{{ $pageRoute }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label class="block text-sm text-slate-700 sm:flex-1">
                Pilih Tanggal
                <input type="date" name="tanggal" value="{{ $selectedDate }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:outline-none" />
            </label>
            <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-6 py-3 text-sm font-semibold text-white">Tampilkan</button>
        </form>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                @if(auth()->user()->role === 'admin')
                    <button type="button" id="deleteToggle" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Hapus</button>
                    <form method="GET" action="{{ route('data.' . $category . '.export') }}" class="inline-flex">
                        <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-4 py-2 text-sm font-semibold text-white">Export</button>
                    </form>
                @endif
            </div>

            <div class="rounded-full bg-slate-100 px-3 py-2 text-sm text-slate-700">
                Total: {{ $laporans->count() }} laporan
            </div>
        </div>

        <form id="deleteForm" method="POST" action="{{ route('data.' . $category . '.delete') }}" class="mt-4">
            @csrf
            <div id="deleteHeader" class="hidden mb-4 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <div class="flex items-center justify-between">
                    <p class="font-semibold">Pilih laporan yang ingin dihapus, lalu tekan Hapus.</p>
                    <div class="flex items-center gap-2">
                        <button type="button" id="cancelDelete" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-rose-700 px-4 py-2 text-sm font-semibold text-white">Hapus yang dipilih</button>
                    </div>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-0 divide-y divide-slate-200 text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900">
                    <tr>
                        @if(auth()->user()->role === 'admin')
                            <th class="px-4 py-3 hidden admin-checkbox-column"></th>
                        @endif
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        @if(auth()->user()->role === 'admin')
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Review</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($laporans as $laporan)
                        <tr class="hover:bg-slate-50">
                            @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-4 hidden admin-checkbox-column">
                                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" name="laporan_ids[]" value="{{ $laporan->id }}" class="delete-checkbox hidden" />
                                        <span class="delete-marker inline-flex h-4 w-4 items-center justify-center rounded border border-slate-300 bg-white text-xs text-transparent">✓</span>
                                    </label>
                                </td>
                            @endif
                            <td class="px-4 py-4">{{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') : '-' }}</td>
                            <td class="px-4 py-4">{{ $laporan->lokasi }} {{ $laporan->ruangan }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $laporan->kondisi === 'Urgent' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-amber-700' }}">{{ $laporan->kondisi }}</span>
                            </td>
                            @php
                                $statusLabel = $laporan->status === 'baru' ? 'Belum' : 'Selesai';
                                $statusClass = $laporan->status === 'baru' ? 'text-rose-600' : 'text-emerald-700';
                            @endphp
                            <td class="px-4 py-4 {{ $statusClass }}">{{ $statusLabel }}</td>
                            <td class="px-4 py-4">{{ $laporan->deskripsi }}</td>
                            @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-4 space-y-2">
                                    <div>
                                        @if($laporan->status === 'baru')
                                            <a href="{{ route('data.' . $category . '.work', $laporan) }}" class="text-emerald-700 hover:text-emerald-900 font-semibold">Kerjakan</a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('data.' . $category . '.edit', $laporan) }}" class="text-slate-700 hover:text-slate-900 font-semibold">Edit</a>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($laporan->status !== 'baru')
                                        <a href="{{ route('data.' . $category . '.review', $laporan) }}" class="text-slate-700 hover:text-slate-900 font-semibold">Review</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 5 }}" class="px-4 py-6 text-center text-slate-500">Tidak ada laporan untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteToggle = document.getElementById('deleteToggle');
            const deleteForm = document.getElementById('deleteForm');
            const deleteHeader = document.getElementById('deleteHeader');
            const cancelDelete = document.getElementById('cancelDelete');
            const checkboxes = Array.from(document.querySelectorAll('.delete-checkbox'));
            const deleteMarkers = Array.from(document.querySelectorAll('.delete-marker'));
            const checkboxColumns = Array.from(document.querySelectorAll('.admin-checkbox-column'));

            // initial state: hide checkbox column, checkboxes, and markers
            checkboxColumns.forEach(col => col.classList.add('hidden'));
            checkboxes.forEach(cb => cb.classList.add('hidden'));
            deleteMarkers.forEach(dm => dm.classList.add('text-transparent'));

            if (deleteToggle && deleteHeader) {
                deleteToggle.addEventListener('click', function () {
                    const opening = deleteHeader.classList.contains('hidden');
                    if (opening) {
                        deleteHeader.classList.remove('hidden');
                        checkboxColumns.forEach(col => col.classList.remove('hidden'));
                        checkboxes.forEach(cb => cb.classList.remove('hidden'));
                        deleteMarkers.forEach(dm => dm.classList.add('text-transparent'));
                        deleteToggle.textContent = 'Batal';
                    } else {
                        deleteHeader.classList.add('hidden');
                        checkboxColumns.forEach(col => col.classList.add('hidden'));
                        checkboxes.forEach(cb => { cb.classList.add('hidden'); cb.checked = false; });
                        deleteMarkers.forEach(dm => dm.classList.add('text-transparent'));
                        deleteToggle.textContent = 'Hapus';
                    }
                });
            }

            if (cancelDelete) {
                cancelDelete.addEventListener('click', function () {
                    if (deleteHeader) {
                        deleteHeader.classList.add('hidden');
                    }
                    checkboxes.forEach(cb => { cb.classList.add('hidden'); cb.checked = false; });
                    deleteMarkers.forEach(dm => dm.classList.add('text-transparent'));
                    if (deleteToggle) deleteToggle.textContent = 'Hapus';
                });
            }

            checkboxes.forEach((checkbox, index) => {
                checkbox.addEventListener('change', function () {
                    if (checkbox.checked) {
                        deleteMarkers[index].classList.remove('text-transparent');
                    } else {
                        deleteMarkers[index].classList.add('text-transparent');
                    }
                });
            });
        });

        function laporanForm(submitUrl) {
            return {
                submitUrl,
                images: [],
                form: {
                    tanggal: new Date().toISOString().slice(0,10),
                    lokasi: '',
                    ruangan: '',
                    kondisi: 'Middle',
                    deskripsi: ''
                },
                handleFiles(e) {
                    const files = Array.from(e.target.files).slice(0,5);
                    this.images = [];
                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (ev) => { this.images.push(ev.target.result); };
                        reader.readAsDataURL(file);
                    });
                },
                removeImage(idx) { this.images.splice(idx,1); },
                resetForm() {
                    this.form = {
                        tanggal: new Date().toISOString().slice(0,10),
                        lokasi: '',
                        ruangan: '',
                        kondisi: 'Middle',
                        deskripsi: ''
                    };
                    this.images = [];
                    this.$refs.fileInput.value = null;
                },
                async submit() {
                    const formData = new FormData();
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    formData.append('_token', token);
                    formData.append('tanggal', this.form.tanggal);
                    formData.append('lokasi', this.form.lokasi);
                    formData.append('ruangan', this.form.ruangan);
                    formData.append('kondisi', this.form.kondisi);
                    formData.append('deskripsi', this.form.deskripsi);

                    const files = Array.from(this.$refs.fileInput.files || []);
                    files.forEach((file, index) => {
                        formData.append(`foto[${index}]`, file);
                    });

                    const response = await fetch(this.submitUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const result = await response.json();
                    if (!response.ok) {
                        alert(result.message || 'Gagal menyimpan laporan.');
                        return;
                    }

                    alert(result.message || 'Laporan berhasil disimpan.');
                    window.location.reload();
                }
            }
        }

        function laporanRow(actionUrl, initialStatus, initialTanggal, initialDetail, initialNamaTukang, initialEstimasi) {
            return {
                open: false,
                loading: false,
                status: initialStatus,
                work: {
                    tanggal_pengerjaan: initialTanggal || new Date().toISOString().slice(0, 10),
                    nama_tukang: initialNamaTukang || '',
                    estimasi: initialEstimasi || '',
                    detail_pengerjaan: initialDetail || '',
                },
                get buttonLabel() {
                    return this.status === 'baru' ? 'Kerjakan' : 'Review';
                },
                get statusText() {
                    if (this.status === 'baru') {
                        return 'Laporan siap dikerjakan';
                    }

                    return 'Laporan dalam review';
                },
                async saveWork() {
                    this.loading = true;
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('tanggal_pengerjaan', this.work.tanggal_pengerjaan);
                    formData.append('nama_tukang', this.work.nama_tukang);
                    formData.append('estimasi', this.work.estimasi);
                    formData.append('detail_pengerjaan', this.work.detail_pengerjaan);

                    const response = await fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const result = await response.json();
                    this.loading = false;

                    if (!response.ok) {
                        alert(result.message || 'Gagal menyimpan detail kerja.');
                        return;
                    }

                    alert(result.message || 'Detail pengerjaan berhasil disimpan.');
                    this.status = 'review';
                    this.open = false;
                    window.location.reload();
                },
            };
        }
    </script>
</x-dashboard-shell>
