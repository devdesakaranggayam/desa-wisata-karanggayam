<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carousels = [
            ['nama' => 'Banner', 'identifier'=>'home_banner'],
            ['nama' => 'Produk', 'identifier'=>'home_produk'],
        ];

        foreach ($carousels as $data) {
            $exists = Carousel::where('identifier', $data["identifier"])->exists();
            if (!$exists) {
                Carousel::create($data);
            }
        }
    }
}
