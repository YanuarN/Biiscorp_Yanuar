<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i=0; $i<20; $i++) {
            Pegawai::create([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'jabatan' => $faker->randomElement(['Manager', 'Staff', 'Supervisor', 'Designer', 'Programmer']),
                'gaji' => $faker->numberBetween(4000000, 10000000),
                'tanggal_lahir' => $faker->date('Y-m-d', '2000-12-31'),
                'foto' => null, // Mengosongkan kolom foto
            ]);
        }
    }
}
