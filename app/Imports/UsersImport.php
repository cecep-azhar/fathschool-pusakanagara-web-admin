<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Email validation
            $query = User::query();
            $email = $row['email'] ? trim($row['email']) : null;
            $new_email = null;
            if ($email) {
                $check_email_is_exist = $query->where('email', $email)->first();
                $new_email = $check_email_is_exist ? Str::random(4) . $email : $email;
            }

            // Parent validation
            $parent = null;
            if ($row['email_wali'] && trim($row['email_wali'])) {
                $user = $query->where('email', trim($row['email_wali']))->first();
                $parent = $user ? $user->id : $query->parent()->inRandomOrder()->value('id');
            } else {
                $parent = $query->parent()->inRandomOrder()->value('id');
            }

            // Class validation
            $class = null;
            if ($row['kelas'] && trim($row['kelas'])) {
                $exit_class = Course::where('slug', trim($row['kelas']))->first();
                $class = $exit_class ? $exit_class->id : null;
            }

            $added_user = User::create([
                'nisn' => isset($row['nisn']) ? trim($row['nisn']) : null,
                'rfid' => isset($row['rfid']) ? trim($row['rfid']) : null,
                'name' => isset($row['name']) && trim($row['name']) ? trim($row['name']) : Str::random(6),
                'date_of_birth' => isset($row['tanggal_lahir']) && trim($row['tanggal_lahir']) ? Carbon::parse($row['tanggal_lahir']) : Carbon::now(),
                'email' => $new_email,
                'role' => 'Student',
                'password' => isset($row['password']) && trim($row['password']) ? bcrypt(trim($row['password'])) : bcrypt('password'),
                'phone' => isset($row['nomor_telepon']) && trim($row['nomor_telepon']) ? trim($row['nomor_telepon']) : null,
            ]);

            if ($parent) {
                $added_user->parents()->attach($parent);
            }

            $added_user->profile()->create([
                'student_id' => idGenerate(),
            ]);

            if ($class) {
                $added_user->courses()->create([
                    'course_id' => $class,
                ]);
            }
        }
    }
}
