<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::insertOrIgnore([
            [
                'key' => 'center_lat_lng',
                'value' => '0,0',
                'desc' => 'Titik koordinat pusat dalam format "latitude,longitude", digunakan sebagai acuan perhitungan radius area bermain pengguna.'
            ],
            [
                'key' => 'radius_km',
                'value' => '10',
                'desc' => 'Jarak radius maksimum (dalam kilometer) yang menentukan apakah pengguna berada di dalam area yang diizinkan untuk bermain game stamp.'
            ],
            [
                'key' => 'min_stamp_sertifikat',
                'value' => '10',
                'desc' => 'Jumlah minimal stamp untuk mendapat sertifikat'
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.1',
                'desc' => 'Versi aplikasi'
            ],
        ]);

    }
}
