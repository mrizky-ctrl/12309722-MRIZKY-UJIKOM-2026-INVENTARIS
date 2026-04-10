@extends('layouts.inventaris')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title">Edit Account Forms</h5>
            <p class="text-muted small">Please <span class="text-danger">.fill-all</span> input form with right value.</p>
        <hr>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- update akun staff --}}
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password <span class="text-warning small">optional</span></label>
                    <input type="password" name="new_password" class="form-control" placeholder="Leave blank if no change">
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4" style="background-color: #6f42c1; border:none;">Submit</button>
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
