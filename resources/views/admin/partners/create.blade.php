@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Partner Baru</h2>

    <form action="{{ route('admin.partners.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Partner</label>
            <input
                type="text"
                name="name"
                id="name"
                class="form-control"
                placeholder="Masukkan nama partner"
                required
            >
        </div>

        <div class="mb-3">
            <label for="logo_url" class="form-label">Logo URL</label>
            {{-- Dropdown pilihan preset URL atau input manual --}}
            <select name="logo_url" id="logo_url" class="form-control" onchange="updateLogoInput(this)">
                <option value="">-- Pilih atau ketik URL --</option>
                <option value="https://placehold.co/200x200?text=Partner+A">Placeholder A (200x200)</option>
                <option value="https://placehold.co/200x200?text=Partner+B">Placeholder B (200x200)</option>
                <option value="https://placehold.co/200x200?text=Partner+C">Placeholder C (200x200)</option>
                <option value="custom">Ketik URL Sendiri...</option>
            </select>

            <input
                type="text"
                name="logo_url_custom"
                id="logo_url_custom"
                class="form-control mt-2"
                placeholder="https://example.com/logo.png"
                style="display:none;"
            >
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="/admin/partners" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    // Jika pilih "Ketik URL Sendiri", tampilkan input manual
    // dan ganti value name="logo_url" dengan input custom
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
</script>
@endsection
