@extends('layouts.admin')

@section('title', 'Kelola Event - Admin')

@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Buat dan atur acara seru Anda di sini.')

@section('content')
<div class="mb-8 grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-black text-slate-800">Daftar Kategori</h2>
                <p class="text-sm text-slate-400 font-medium">Kategori yang bisa dipilih saat membuat event.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-block px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                + Tambah Kategori
            </a>
        </div>

        <form action="{{ route('admin.events.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-3">
            @if($eventSearch)
                <input type="hidden" name="event_search" value="{{ $eventSearch }}">
            @endif
            <input
                type="text"
                name="category_search"
                value="{{ $categorySearch }}"
                class="flex-1 px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Cari kategori..."
            >
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition">
                    Cari
                </button>
                @if($categorySearch)
                    <a href="{{ route('admin.events.index', array_filter(['event_search' => $eventSearch])) }}" class="px-5 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @forelse($categories as $category)
                <div class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                    <div class="min-w-0">
                        <p class="font-black text-slate-800 truncate">{{ $category->name }}</p>
                        <p class="text-xs text-slate-400">{{ $category->slug }} &middot; {{ $category->events_count }} event</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Edit Kategori">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>

                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm" title="Hapus Kategori">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 px-6 py-8 text-center text-slate-500 font-medium border border-dashed border-slate-200 rounded-2xl">
                    Belum ada kategori yang ditambahkan.
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-indigo-600 rounded-[2.5rem] shadow-lg shadow-indigo-100 p-6 text-white flex flex-col justify-between gap-6">
        <div>
            <p class="text-indigo-200 font-bold uppercase tracking-widest text-xs mb-2">Event</p>
            <h2 class="text-2xl font-black">Tambah acara baru</h2>
            <p class="text-indigo-100 text-sm mt-2">Buat event dan pilih kategori yang sudah tersedia.</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="inline-block text-center px-6 py-4 bg-white text-indigo-600 rounded-2xl font-black hover:scale-[1.02] transition-transform shadow-xl">
            + Tambah Event Baru
        </a>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Daftar Event</h2>
            <p class="text-sm text-slate-400 font-medium">Cari event berdasarkan nama event.</p>
        </div>

        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 lg:w-[520px]">
            @if($categorySearch)
                <input type="hidden" name="category_search" value="{{ $categorySearch }}">
            @endif
            <input
                type="text"
                name="event_search"
                value="{{ $eventSearch }}"
                class="flex-1 px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Cari event..."
            >
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition">
                    Cari
                </button>
                @if($eventSearch)
                    <a href="{{ route('admin.events.index', array_filter(['category_search' => $categorySearch])) }}" class="px-5 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">
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
                    <th class="px-8 py-4">Poster</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Harga / Stok</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($events as $index => $event)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400">
                            {{ $events->firstItem() + $index }}
                        </td>
                        <td class="px-8 py-6">
                            @if($event->poster_path)
                                <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-16 h-20 rounded-xl object-cover shadow-sm" alt="Poster">
                            @else
                                <img src="https://placehold.co/160x200?text=No+Image" class="w-16 h-20 rounded-xl object-cover shadow-sm">
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-800">{{ $event->title }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $event->category->name ?? 'Tanpa Kategori' }} • {{ $event->date->format('d M Y') }}
                            </p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-bold text-indigo-600">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-400">Stok: {{ $event->stock }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm">
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
                        <td colspan="5" class="px-8 py-10 text-center text-slate-500 font-medium">
                            Belum ada acara yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 py-6 bg-slate-50/50 border-t">
        {{ $events->links() }}
    </div>
</div>
@endsection
