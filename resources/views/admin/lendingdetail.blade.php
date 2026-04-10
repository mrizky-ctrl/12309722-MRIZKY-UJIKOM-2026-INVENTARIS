@extends('layouts.inventaris')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Lending Table</h4>
                    <p class="text-muted small">Data of <span class="text-danger">.lendings</span></p>
                </div>
                <a href="{{ route('items.index') }}" class="btn btn-secondary px-4">Back</a>
            </div>

            <table class="table align-middle">
                <thead class="table-light">
                    <tr class="text-muted">
                        <th>#</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Name</th>
                        <th>Ket.</th>
                        <th>Date</th>
                        <th>Returned</th>
                        <th>Edited By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->lendings as $index => $lend)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-secondary">{{ $item->name }}</td>
                            <td>{{ $lend->qty ?? $lend->total }}</td>
                            <td>{{ $lend->name ?? $lend->borrower_name }}</td>
                            <td>{{ $lend->notes ?? 'tidak ada keterangan' }}</td>
                            <td>{{ $lend->created_at->format('d F, Y') }}</td>
                            <td>
                                @if ($lend->is_returned)
                                    <span class="badge bg-success-subtle text-success">returned</span>
                                @else
                                    <span class="badge bg-white text-warning"
                                        style="border: 1px solid orange; padding: 5px 10px;">not returned</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $lend->user->name ?? 'operator wikrama' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
