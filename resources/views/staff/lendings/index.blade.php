@extends('layouts.inventaris')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Lending Table</h5>
                    <small class="text-muted">Data of <span class="text-danger">.lendings</span></small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('lendings.export') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('lendings.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg"></i> + Add
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-hover mt-3">
                    <thead>
                        <tr class="text-muted">
                            <th>#</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Name</th>
                            <th>Ket.</th>
                            <th>Date</th>
                            <th>Returned</th>
                            <th>Edited By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lendings as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->item->name }}</td>
                                <td>{{ $row->qty }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->notes }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->date_lending)->format('d F, Y') }}</td>
                                <td>
                                    @if ($row->is_returned)
                                        <span
                                            class="badge border text-success">{{ $row->updated_at->format('d F, Y') }}</span>
                                    @else
                                        <span class="badge border text-warning">not returned</span>
                                    @endif
                                </td>
                                <td><strong>{{ $row->user->name }}</strong></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if (!$row->is_returned)
                                            <form action="{{ route('lendings.update', $row->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button class="btn btn-warning btn-sm text-white">Returned</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('lendings.destroy', $row->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
