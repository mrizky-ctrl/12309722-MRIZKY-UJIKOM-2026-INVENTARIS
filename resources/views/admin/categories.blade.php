@extends('layouts.inventaris')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold">Categories Table</h4>
                    <p class="text-muted small">Add, delete, update <span class="text-danger">.categories</span></p>
                </div>
                <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-square-fill me-2"></i> Add
                </button>
            </div>

            <table class="table align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>#</th>
                        <th>Name</th>
                        <th>Division PJ</th>
                        <th>Total Items</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $cat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-secondary">{{ $cat->name }}</td>
                            <td>{{ $cat->division_pj }}</td>
                            <td>{{ $cat->items_count }}</td>
                            <td class="text-center">
                                <button class="btn btn-primary" style="background-color: #6f42c1; border:none;"
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-3">

                                    <form action="{{ route('categories.update', $cat->id) }}" method="POST" id="updateForm{{ $cat->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body text-start">
                                            <h5 class="fw-bold text-primary">Edit Category Forms</h5>
                                            <p class="text-muted small">Update information for <span class="text-danger">{{ $cat->name }}</span></p>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Division PJ</label>
                                                <select name="division_pj" class="form-select" required>
                                                    <option value="Sarpras" {{ $cat->division_pj == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                                                    <option value="Tata Usaha" {{ $cat->division_pj == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                                    <option value="Tefa" {{ $cat->division_pj == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-0 d-flex justify-content-between">
                                            <button type="submit" form="deleteForm{{ $cat->id }}" class="btn btn-outline-danger px-4"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                Delete
                                            </button>

                                            <div>
                                                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary px-4" style="background-color: #6f42c1; border:none;">
                                                    Update Data
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" id="deleteForm{{ $cat->id }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
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
            <div class="modal-content p-3">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body text-start">
                        <h5 class="fw-bold text-primary">Add Category Forms</h5>
                        <p class="text-muted small">Please fill all input form with right value.</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Alat Dapur" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Division PJ</label>
                            <select name="division_pj" class="form-select @error('division_pj') is-invalid @enderror">
                                <option selected disabled>Select Division PJ</option>
                                <option value="Sarpras" {{ old('division_pj') == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                                <option value="Tata Usaha" {{ old('division_pj') == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                <option value="Tefa" {{ old('division_pj') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                            </select>
                            @error('division_pj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="background-color: #6f42c1; border:none;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
