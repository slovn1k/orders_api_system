<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Disscount', 'slug' => 'disscount'],
            ['name' => 'Vip', 'slug' => 'vip'],
            ['name' => 'Priority', 'slug' => 'priority'],
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->updateOrInsert(
                ['slug' => $tag['slug']],
                [
                    'name' => $tag['name'],
                    'slug' => $tag['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
