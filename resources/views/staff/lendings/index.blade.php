@extends('layouts.inventaris')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Lending Table</h5>
                    <small class="text-muted">Data of <span class="text-danger">.lendings</span></small>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Transaksi</h6>
                            <h3>{{ $stats['total_transaksi'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Barang</h6>
                            <h3>{{ $stats['total_barang'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark shadow-sm">
                        <div class="card-body">
                            <h6>Barang Dipinjam</h6>
                            <h3>{{ $stats['dipinjam'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow-sm">
                        <div class="card-body">
                            <h6>Belum Kembali</h6>
                            <h3>{{ $stats['belum_kembali'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('lendings.index') }}" class="row g-2 mb-4">
                    <div class="col-md-4 d-flex gap-2 align-items-end">
                        <a href="{{ route('lendings.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-lg"></i> + Add
                        </a>
                        <a href="{{ route('lendings.export') }}" class="btn btn-primary">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </a>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-1">
                            <div class="col-md-3">
                                <label class="small text-muted">Cari Nama/Barang</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    value="{{ request('search') }}" placeholder="Search...">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Tgl Pinjam</label>
                                <input type="date" name="date_lending" class="form-control form-control-sm"
                                    value="{{ request('date_lending') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Tgl Kembali</label>
                                <input type="date" name="date_returned" class="form-control form-control-sm"
                                    value="{{ request('date_returned') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Sudah Kembali
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Kembali
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-1">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Cari</button>
                                <a href="{{ route('lendings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-hover mt-3">
                    <thead>
                        <tr class="text-muted border-bottom">
                            <th>#</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Name</th>
                            <th>Ket.</th>
                            <th>Date Lending</th>
                            <th>Returned At</th>
                            <th>Condition</th>
                            <th>Denda & Status</th>
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
                                <td>
                                    @if ($row->condition)
                                        <span
                                            class="badge @if ($row->condition == 'Baik') bg-success @elseif($row->condition == 'Rusak') bg-danger @elseif($row->condition == 'Perlu Perbaikan') bg-warning @else bg-secondary @endif">
                                            {{ $row->condition }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($row->penalty_amount > 0)
                                        <span class="badge {{ $row->is_penalty_paid ? 'bg-success' : 'bg-danger' }}">
                                            {{ $row->is_penalty_paid ? 'Lunas' : 'Denda: Rp ' . number_format($row->penalty_amount) }}
                                        </span>
                                        @if (!$row->is_penalty_paid)
                                            <form action="{{ route('lendings.payPenalty', $row->id) }}" method="POST"
                                                class="mt-1">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-xs btn-outline-primary"
                                                    style="font-size: 10px;">Bayar Lunas</button>
                                            </form>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><strong>{{ $row->user->name }}</strong></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if (!$row->is_returned)
                                            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                                data-bs-target="#returnModal{{ $row->id }}">Returned</button>

                                            <div class="modal fade" id="returnModal{{ $row->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('lendings.update', $row->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Form Pengembalian (Total:
                                                                    {{ $row->qty }})</h5>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label class="small">Baik</label>
                                                                        <input type="number" name="qty_baik"
                                                                            class="form-control mb-2"
                                                                            value="{{ $row->qty }}" min="0"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="small">Rusak</label>
                                                                        <input type="number" name="qty_rusak"
                                                                            class="form-control mb-2" value="0"
                                                                            min="0" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="small">Hilang</label>
                                                                        <input type="number" name="qty_hilang"
                                                                            class="form-control mb-2" value="0"
                                                                            min="0" required>
                                                                    </div>
                                                                </div>
                                                                <label class="small">Nominal Denda (Rp)</label>
                                                                <input type="number" name="penalty_amount"
                                                                    class="form-control" placeholder="0" min="0"
                                                                    required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Simpan
                                                                    Pengembalian</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        <form action="{{ route('lendings.destroy', $row->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        <a href="{{ route('lendings.print', $row->id) }}" class="btn btn-sm btn-info"
                                            target="_blank">
                                            <i class="bi bi-printer"></i> Cetak
                                        </a>
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
