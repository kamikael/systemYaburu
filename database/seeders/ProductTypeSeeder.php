<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Numérique',
                'description' => 'Produits digitaux téléchargeables comme ebooks, logiciels, formations ou fichiers.',
            ],
            [
                'name' => 'Service',
                'description' => 'Prestations de services comme consulting, coaching, design ou maintenance.',
            ],
            [
                'name' => 'Physique',
                'description' => 'Produits matériels nécessitant une livraison physique.',
            ],
        ];

        foreach ($types as $type) {

            ProductType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                ]
            );
        }

        $this->command->info('✅ Product types créés avec succès.');
    }
}