<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan1 = new Karyawan();
        $karyawan1->user_id = 2;
        $karyawan1->nama_lengkap = 'Vikar Maulana Arrisyad';
        $karyawan1->jabatan = 'Manager';
        $karyawan1->nik = '33281212391023';
        $karyawan1->no_hp = '0878604293';
        $karyawan1->save();
    }
}
