@extends('layouts.admin')

@section('page_title', 'Tambah Partner Baru')
@section('page_subtitle', 'Tambahkan nama dan logo partner.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Partner</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="Masukkan nama partner"
                required
            >
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="logo_url" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Logo URL</label>
            <select
                name="logo_url"
                id="logo_url"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                onchange="updateLogoInput(this)"
            >
                <option value="">-- Pilih atau ketik URL --</option>
                <option value="https://placehold.co/200x200?text=Partner+A" {{ old('logo_url') === 'https://placehold.co/200x200?text=Partner+A' ? 'selected' : '' }}>Placeholder A (200x200)</option>
                <option value="https://placehold.co/200x200?text=Partner+B" {{ old('logo_url') === 'https://placehold.co/200x200?text=Partner+B' ? 'selected' : '' }}>Placeholder B (200x200)</option>
                <option value="https://placehold.co/200x200?text=Partner+C" {{ old('logo_url') === 'https://placehold.co/200x200?text=Partner+C' ? 'selected' : '' }}>Placeholder C (200x200)</option>
                <option value="custom" {{ old('logo_url_custom') ? 'selected' : '' }}>Ketik URL Sendiri...</option>
            </select>

            <input
                type="url"
                name="logo_url_custom"
                id="logo_url_custom"
                value="{{ old('logo_url_custom') }}"
                class="w-full px-5 py-4 mt-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                placeholder="https://example.com/logo.png"
                style="display:none;"
            >
            @error('logo_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Simpan Partner
            </button>
        </div>
    </form>
</div>

<script>
    function updateLogoInput(select) {
        const customInput = document.getElementById('logo_url_custom');
        if (select.value === 'custom') {
            customInput.style.display = 'block';
            customInput.name = 'logo_url';
            select.name = 'logo_url_dropdown';
        } else {
            customInput.style.display = 'none';
            customInput.name = 'logo_url_custom';
            select.name = 'logo_url';
        }
    }

    updateLogoInput(document.getElementById('logo_url'));
</script>
@endsection
