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
    // Tabel Kategori
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('division_pj');
        $table->timestamps();
    });

    // Tabel Barang (Items)
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->integer('stock');
        $table->integer('available')->default(0);
        $table->timestamps();
    });

    // Tabel Peminjaman (Lending)
    Schema::create('lendings', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('item_id')->constrained()->onDelete('cascade');
        $table->integer('qty');
        $table->dateTime('date_lending');
        $table->boolean('is_returned')->default(false);
        $table->text('notes')->nullable();
        // Kolom untuk fitur Tambahan
        $table->longText('signature')->nullable(); // Untuk tanda tangan digital
        $table->integer('qty_broken')->default(0);
        $table->decimal('penalty_amount', 12, 2)->default(0);
        $table->boolean('is_penalty_paid')->default(true);
        $table->string('condition')->nullable();
        $table->timestamps();
    });
}
};
