<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only seed if database is empty
        // Check both categories and articles to prevent duplicate seeding
        if (\App\Models\Category::count() === 0 && Article::count() === 0) {
            $this->command->info('Seeding database with initial data...');

            $this->call([
                CategorySeeder::class,
                TagSeeder::class,
                ArticleSeeder::class,
            ]);

            $this->command->info('Database seeded successfully!');
        } else {
            $this->command->info('Database already contains data. Skipping seed.');
        }
    }
}
