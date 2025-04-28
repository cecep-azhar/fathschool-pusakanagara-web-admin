<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TeachersImport implements ToCollection, WithHeadingRow
{
    use \Maatwebsite\Excel\Concerns\Importable;

    public function collection(Collection $rows)
    {
        Log::info('Starting teacher import process', ['row_count' => $rows->count()]);

        foreach ($rows as $row) {
            // Debugging: Log data baris untuk memastikan data dibaca dengan benar
            Log::info('Row Data:', $row->toArray());

            // Validasi NIP
            $nip = $row['Nip'] ?? null; // Perhatikan huruf kapital "Nip"
            if (!$nip) {
                Log::warning('Skipping row due to missing NIP');
                continue;
            }

            // Validasi Email
            $email = $row['Email'] ?? null; // Perhatikan huruf kapital "Email"
            if ($email && User::where('email', $email)->exists()) {
                Log::warning('Skipping duplicate email', ['email' => $email]);
                continue;
            }

            // Validasi Nomor Telepon
            $phone = $row['Nomor Telepon'] ?? null; // Perhatikan spasi "Nomor Telepon"

            // Buat pengguna baru
            try {
                $added_user = User::create([
                    'nip' => $nip,
                    'rfid' => $row['Rfid'] ?? null, // Perhatikan huruf kapital "Rfid"
                    'name' => $row['Name'] ?? 'Nama Tidak Diketahui', // Perhatikan huruf kapital "Name"
                    'email' => $email,
                    'role' => 'Teacher',
                    'password' => bcrypt($row['Password'] ?? 'password'), // Default password jika kosong
                    'phone' => $phone,
                ]);

                Log::info('Teacher created', ['nip' => $nip, 'email' => $email]);

                // Buat profil pengguna
                $added_user->profile()->create([
                    'student_id' => idGenerate(), // Menggunakan fungsi uniqid
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating teacher', ['error' => $e->getMessage()]);
            }
        }

        Log::info('Teacher import process completed');
    }
}
