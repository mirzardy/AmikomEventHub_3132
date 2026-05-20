@extends('layouts.admin')

@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Atur daftar partner dan logo yang tampil di sistem.')

@section('content')
<div class="mb-4 text-right">
    <a href="{{ route('admin.partners.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
        + Tambah Partner
    </a>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Daftar Partner</h2>
            <p class="text-sm text-slate-400 font-medium">Cari partner berdasarkan nama.</p>
        </div>

        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 lg:w-[520px]">
            <input
                type="text"
                name="partner_search"
                value="{{ $partnerSearch }}"
                class="flex-1 px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Cari partner..."
            >
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition">
                    Cari
                </button>
                @if($partnerSearch)
                    <a href="{{ route('admin.partners.index') }}" class="px-5 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4">Logo</th>
                    <th class="px-8 py-4">Nama Partner</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($partners as $index => $partner)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-8 py-6">
                            <img src="{{ $partner->logo_url ?: 'https://placehold.co/200x200?text=Partner' }}" alt="{{ $partner->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-100 shadow-sm">
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-800">{{ $partner->name }}</p>
                            <p class="text-xs text-slate-400 truncate max-w-md">{{ $partner->logo_url }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Edit Partner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm" title="Hapus Partner">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-slate-500 font-medium">
                            Belum ada partner yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
