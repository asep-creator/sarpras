<x-dashboard-shell title="Pengguna" subtitle="Kelola akun terdaftar" activeMenu="pengguna">
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Daftar Pengguna</h3>
                <p class="mt-1 text-sm text-slate-500">Lihat, tambah, edit, dan hapus akun.</p>
            </div>
            <a href="{{ route('pengguna.create') }}" class="inline-flex items-center justify-center rounded-3xl bg-emerald-950 px-4 py-2 text-sm font-semibold text-white">Tambah Akun</a>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[760px] divide-y divide-slate-200 text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Dibuat</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-4 py-4">{{ $user->email }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $user->role === 'admin' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-4 space-x-2">
                                <a href="{{ route('pengguna.edit', $user) }}" class="inline-flex rounded-3xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                                <form action="{{ route('pengguna.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex rounded-3xl bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-800" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada akun terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-shell>
