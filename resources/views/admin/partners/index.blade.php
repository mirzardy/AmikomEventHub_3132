@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Partner</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary mb-3">+ Tambah Partner</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Logo</th>
                <th>Nama Partner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partners as $index => $partner)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" width="80">
                </td>
                <td>{{ $partner->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection