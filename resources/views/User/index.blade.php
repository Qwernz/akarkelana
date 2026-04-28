<x-app-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-stone-800">Manajemen Akun User</h2>
        <p class="text-sm text-stone-500">Daftar seluruh pengguna yang terdaftar di sistem Akar Kelana Coffee.</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-bold">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-100">
                        <th class="px-6 py-4 text-xs font-black text-stone-500 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-black text-stone-500 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-xs font-black text-stone-500 uppercase tracking-widest text-center">Role</th>
                        <th class="px-6 py-4 text-xs font-black text-stone-500 uppercase tracking-widest text-center">Tgl Terdaftar</th>
                        <th class="px-6 py-4 text-xs font-black text-stone-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-stone-800">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-stone-600 text-sm">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : ($user->role === 'kasir' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-stone-400 text-sm">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-stone-400 hover:text-red-600 transition-colors p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @else
                            <span class="text-[10px] text-stone-300 italic">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-400 italic">
                            Belum ada user yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>