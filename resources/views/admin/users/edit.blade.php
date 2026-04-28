<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-stone-800">Edit Role User</h2>
        <p class="text-sm text-stone-500">Ubah hak akses untuk <strong>{{ $user->name }}</strong></p>
    </div>

    <div class="max-w-md bg-white p-8 rounded-2xl shadow-sm border border-stone-200">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-xs font-black text-stone-500 uppercase tracking-widest mb-2">Pilih Role</label>
                <select name="role" class="w-full border-stone-200 rounded-xl focus:ring-stone-800 focus:border-stone-800">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User (Pelanggan)</option>
                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir (Staff)</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Pemilik)</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-stone-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-stone-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-stone-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>