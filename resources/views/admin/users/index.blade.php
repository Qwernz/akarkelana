<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-stone-800 tracking-tight">Manajemen Akun User</h2>
        <p class="text-sm text-stone-500">Daftar semua pengguna yang terdaftar di sistem Akar Kelana.</p>
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
                    <tr class="bg-stone-50 border-b border-stone-100 text-[10px] font-black text-stone-500 uppercase tracking-widest">
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-center">Tgl Terdaftar</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-stone-800">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                            <span class="ml-2 text-[9px] bg-stone-800 text-white px-2 py-0.5 rounded-full font-normal italic">Anda</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-stone-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : ($user->role === 'kasir' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-stone-400">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-stone-300 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" ...>
                                ...
                            </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-stone-300 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-400 italic font-medium">
                            Tidak ada data user ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>