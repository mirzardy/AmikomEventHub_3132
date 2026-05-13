@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Daftar Partner</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Logo URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($partners as $index => $partner)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $partner->name }}</td>
                <td>{{ $partner->logo_url }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
