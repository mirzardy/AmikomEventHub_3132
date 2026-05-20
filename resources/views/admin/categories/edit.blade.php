@extends('layouts.admin')

@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Ubah nama dan slug kategori.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Kategori</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $category->name) }}"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                required
            >
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Slug URL</label>
            <input
                type="text"
                name="slug"
                id="slug"
                value="{{ old('slug', $category->slug) }}"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Kosongkan untuk dibuat otomatis"
            >
            @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
