<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    // 1. Tambahkan 'total' (pengganti stock) dan 'repair' agar bisa disimpan ke DB
    protected $fillable = ['category_id', 'name', 'total', 'repair'];
    // Relasi ke kategori (Sudah benar)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //Relasi ke tabel Lending (Peminjaman)
    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }

    // Menghitung jumlah barang yang sedang dipinjam (belum kembali)
    public function getLendingTotalAttribute()
    {
        return $this->lendings()->where('is_returned', false)->sum('qty');
    }
    // Menghitung stok yang tersedia (Available)
    public function getAvailableAttribute()
    {
        // Rumus: Total - (Peminjaman Aktif + Barang Rusak)
        return $this->total - ($this->lending_total + $this->repair);
    }
}
