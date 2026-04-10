<?php

namespace App\Exports;

use App\Models\Lending;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LendingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Ambil data beserta relasi item dan user (penginput)
        return Lending::with(['item', 'user'])->get();
    }

    /**
     * Menentukan Judul Header (Baris 1)
     */
    public function headings(): array
    {
        return [
            'Item',
            'Total',
            'Name',
            'Ket.',
            'Date',
            'Return Date',
            'Edited By'
        ];
    }

    /**
     * Memetakan data ke kolom yang sesuai
     */
    public function map($lending): array
    {
        return [
            $lending->item->name,
            $lending->qty,
            $lending->name,
            $lending->notes ?? '-',
            \Carbon\Carbon::parse($lending->date_lending)->format('M d, Y'),
            // Logika Return Date: Jika sudah kembali tampilkan tanggal update, jika belum tampilkan "-"
            $lending->is_returned
                ? \Carbon\Carbon::parse($lending->updated_at)->format('M d, Y')
                : '-',
            $lending->user->name, // Nama penginput (Edited By)
        ];
    }
}
