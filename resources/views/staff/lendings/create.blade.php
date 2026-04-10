@extends('layouts.inventaris')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Lending Form</h5>
                <p class="text-muted small">Please <span class="text-danger">.fill-all</span> input form with right value.</p>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('lendings.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div id="items-container">
                        <div class="item-row border p-3 mb-3 bg-light rounded position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 btn-remove d-none"
                                aria-label="Close"></button>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Items</label>
                                    <select name="item_id[]" class="form-select" required>
                                        <option value="" disabled selected>Select Items</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} (Available: {{ $item->available }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Total</label>
                                    <input type="number" name="qty[]" class="form-control" placeholder="total item"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="button" id="add-more" class="btn btn-link text-info p-0 text-decoration-none">
                            <i class="bi bi-chevron-down"></i> More
                        </button>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ket.</label>
                        <textarea name="notes" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Date</label>
                        <input type="datetime-local" name="date_lending" class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4"
                            style="background-color: #6f42c1; border:none;">Submit</button>
                        <a href="{{ route('lendings.index') }}" class="btn btn-light px-4 border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('add-more').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const rows = container.getElementsByClassName('item-row');
            const newRow = rows[0].cloneNode(true);
            // Reset nilai input pada row baru
            newRow.querySelector('select').value = "";
            newRow.querySelector('input').value = "";

            // Tampilkan tombol 'X' (Remove) pada row baru
            const removeBtn = newRow.querySelector('.btn-remove');
            removeBtn.classList.remove('d-none');
            removeBtn.onclick = function() {
                newRow.remove();
            };
            container.appendChild(newRow);
        });
    </script>
@endsection
