@extends('layouts.inventaris')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold">Items Table</h4>
                    <p class="text-muted small">Add, delete, update <span class="text-danger">.items</span></p>
                </div>
                <div class="d-flex gap-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('items.export') }}" class="btn btn-primary px-4"
                            style="background-color: #6f42c1; border:none;">
                            Export Excel
                        </a>
                        <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="bi bi-plus-square-fill me-2"></i> Add
                        </button>
                    </div>
                </div>
            </div>

            <table class="table align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>#</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Repair</th>
                        <th>Lending</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td class="fw-bold text-secondary">{{ $item->name }}</td>
                            <td>{{ $item->total }}</td>
                            <td>{{ $item->repair }}</td>
                            <td>
                                @if ($item->lendings_count > 0)
                                    <a href="{{ route('items.lending_detail', $item->id) }}"
                                        class="text-primary fw-bold text-decoration-underline">
                                        {{ $item->lendings_count }}
                                    </a>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary" style="background-color: #6f42c1; border:none;"
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">Edit</button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-3">
                                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body text-start">
                                            <h5 class="fw-bold text-primary">Edit Item Forms</h5>
                                            <p class="text-muted small">Please <span class="text-danger">.fill-all</span>
                                                input form with right value.</p>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $item->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Category</label>
                                                <select name="category_id" class="form-select select2-edit" required>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Total</label>
                                                <input type="number" name="total" class="form-control"
                                                    value="{{ $item->total }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">New Broke Item <span
                                                        class="text-warning small">(currently:
                                                        {{ $item->repair }})</span></label>
                                                <input type="number" name="new_broke_item" class="form-control"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"
                                                style="background-color: #6f42c1; border:none;">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    <div class="modal-body text-start">
                        <h5 class="fw-bold text-primary">Add Item Forms</h5>
                        <p class="text-muted small">Please <span class="text-danger">.fill-all</span> input form with right
                            value.</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Alat Dapur" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id"
                                class="form-select select2-add @error('category_id') is-invalid @enderror">
                                <option value="" selected disabled>Pilih Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Total</label>
                            <div class="input-group">
                                <input type="number" name="total"
                                    class="form-control @error('total') is-invalid @enderror" placeholder="10"
                                    value="{{ old('total') }}">
                                <span class="input-group-text">item</span>
                            </div>
                            @error('total')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #6f42c1; border:none;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2-add, .select2-edit').select2({
                dropdownParent: $('.modal'),
                width: '100%'
            });

            // Buka modal otomatis jika ada error validasi
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addModal'));
                myModal.show();
            @endif
        });
    </script>
@endpush
