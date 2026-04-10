<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Cek jika kolom 'stock' ada, kita ubah jadi 'total'
            if (Schema::hasColumn('items', 'stock')) {
                $table->renameColumn('stock', 'total');
            } else {
                // Jika tidak ada 'stock', buat kolom 'total' baru
                $table->integer('total')->after('name')->nullable();
            }
            // Tambahkan kolom 'repair' untuk logika barang rusak
            if (!Schema::hasColumn('items', 'repair')) {
                $table->integer('repair')->default(0)->after('total');
            }
        });
    }
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('total', 'stock');
            $table->dropColumn('repair');
        });
    }
};
