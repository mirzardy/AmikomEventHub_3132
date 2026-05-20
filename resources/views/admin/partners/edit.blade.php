@extends('layouts.admin')

@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Ubah nama dan logo partner.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Partner</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $partner->name) }}"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Masukkan nama partner"
                required
            >
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="logo_url" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">URL</label>
            <input
                type="url"
                name="logo_url"
                id="logo_url"
                value="{{ old('logo_url', $partner->logo_url) }}"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="https://example.com/logo.png"
            >
            @error('logo_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

            @if($partner->logo_url)
                <div class="mt-4 flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-100">
                    <div>
                        <p class="font-bold text-slate-700">Logo saat ini</p>
                        <p class="text-xs text-slate-400 break-all">{{ $partner->logo_url }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
