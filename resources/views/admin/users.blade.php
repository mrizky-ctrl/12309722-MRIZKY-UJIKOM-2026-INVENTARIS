@extends('layouts.inventaris')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold">Admin Accounts Table</h4>
                    <p class="text-muted small mb-0">Add, delete, update <span class="text-danger">.admin-accounts</span>
                    </p>
                    <p class="text-primary small">p.s password <span class="text-muted">4 character of email and
                            nomor.</span></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.export', ['role' => $role]) }}" class="btn btn-primary px-4"
                        style="background-color: #6f42c1; border:none;">Export Excel</a>
                    <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-lg"></i> Add
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $user->id }}">Edit</button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3"
                                            onclick="return confirm('Yakin hapus?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="fw-bold">Edit Account Forms</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted small">Please <span
                                                        class="text-danger">.fill-all</span> input form with right value.
                                                </p>
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">New Password <span
                                                            class="text-warning small">optional</span></label>
                                                    <input type="password" name="new_password" class="form-control"
                                                        placeholder="Kosongkan jika tidak ingin ganti">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary"
                                                    style="background-color: #6f42c1; border:none;">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="fw-bold">Add Account Forms</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Please <span class="text-danger">.fill-all</span> input form with right
                            value.</p>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="rizky@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>admin</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>staff
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #6f42c1; border:none;">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
