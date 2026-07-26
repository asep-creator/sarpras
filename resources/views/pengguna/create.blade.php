<x-dashboard-shell title="Tambah Pengguna" subtitle="Buat akun baru" activeMenu="pengguna">
    <div class="max-w-2xl">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('pengguna.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                        @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                        @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Role</label>
                        <select name="role" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                        @error('password')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" required>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('pengguna.index') }}" class="inline-flex rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                        <button type="submit" class="inline-flex rounded-3xl bg-emerald-950 px-4 py-3 text-sm font-semibold text-white">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-shell>
