<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
public function run(): void
{
    User::insert([
        [
            'nama_mahasiswa' => 'Dimas Erlansyah',
            'username' => 'dim',
            'email' => 'dimas@example.com',
            'password' => Hash::make('password123'),
            'jenis_kelamin' => 'Laki-Laki',
            'deskripsi' => 'saya adalah seorang dosen',
            'id_jurusan' => 1,
            'id_keahlian' => 1,
            'id_angkatan' => 1,
            'is_active' => true,
            'role' => 'mahasiswa'
        ],
        [
            'nama_mahasiswa' => 'Fauzan',
            'username' => 'OjanCoeg',
            'email' => 'Ojan@example.com',
            'password' => Hash::make('password123'),
            'jenis_kelamin' => 'Laki-Laki',
            'deskripsi' => 'saya adalah seorang Mahasiswa Teladan',
            'id_jurusan' => 2,
            'id_keahlian' => 2,
            'id_angkatan' => 1,
            'is_active' => true,
            'role' => 'mahasiswa'
        ],
        [
            'nama_mahasiswa' => 'Atan',
            'username' => 'atan',
            'email' => 'Atan@example.com',
            'password' => Hash::make('password123'),
            'jenis_kelamin' => 'Laki-Laki',
            'deskripsi' => 'saya adalah seorang Ambatunat',
            'id_jurusan' => 3,
            'id_keahlian' => 3,
            'id_angkatan' => 2,
            'is_active' => true,
            'role' => 'mahasiswa'
        ],
        [
            'nama_mahasiswa' => 'admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'jenis_kelamin' => 'Laki-Laki',
            'deskripsi' => 'saya adalah seorang admin',
            'id_jurusan' => 3,
            'id_keahlian' => 3,
            'id_angkatan' => 2,
            'is_active' => true,
            'role' => 'dosen'
        ]
    ]);
}
}
