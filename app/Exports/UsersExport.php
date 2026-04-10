<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize};

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        // Mengambil semua user berdasarkan role yang dipilih
        return User::where('role', $this->role)->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Password'];
    }

    public function map($user): array
    {
        // Karena operator sudah jadi staff, logika password tetap sama
        if ($user->password_changed_at) {
            $passwordDisplay = 'This account already edited the password';
        } else {
            $emailPrefix = substr($user->email, 0, 4);
            $passwordDisplay = $emailPrefix . $user->id;
        }

        return [
            $user->name,
            $user->email,
            $passwordDisplay
        ];
    }
}
