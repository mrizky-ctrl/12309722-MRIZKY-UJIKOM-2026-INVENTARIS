@extends('layouts.inventaris')

@section('title', 'Staff Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-20 flex flex-col items-center justify-center text-center">
        <div class="mb-6">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center border border-blue-100">
                 <span class="text-4xl">🧑‍💻</span>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Welcome Back, Staff!</h2>
        <p class="text-gray-500 max-w-md mx-auto leading-relaxed">
            Anda masuk sebagai Staff. Silahkan kelola data barang dan peminjaman (Lendings) melalui menu di samping.
        </p>
    </div>
</div>
@endsection
