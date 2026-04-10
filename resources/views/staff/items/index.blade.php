@extends('layouts.inventaris')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Items Table</h5>
            <small class="text-muted">Data of <span class="text-danger">.items</span></small>
        </div>
        <div class="card-body">
            <table class="table table-hover mt-3">
                <thead>
                    <tr class="text-muted">
                        <th>#</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Available</th>
                        <th>Lending Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->total }}</td>
                        <td class="fw-bold text-primary">
                            {{ $item->available }}
                        </td>
                        <td>
                            {{ $item->lending_total }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
