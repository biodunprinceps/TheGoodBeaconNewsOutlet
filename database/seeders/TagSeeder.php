<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Breaking News', 'slug' => 'breaking-news'],
            ['name' => 'Trending', 'slug' => 'trending'],
            ['name' => 'Featured', 'slug' => 'featured'],
            ['name' => 'Exclusive', 'slug' => 'exclusive'],
            ['name' => 'Interview', 'slug' => 'interview'],
            ['name' => 'Opinion', 'slug' => 'opinion'],
            ['name' => 'Analysis', 'slug' => 'analysis'],
            ['name' => 'Investigation', 'slug' => 'investigation'],
            ['name' => 'Local', 'slug' => 'local'],
            ['name' => 'International', 'slug' => 'international'],
            ['name' => 'Climate Change', 'slug' => 'climate-change'],
            ['name' => 'Economy', 'slug' => 'economy'],
            ['name' => 'Elections', 'slug' => 'elections'],
            ['name' => 'AI & Machine Learning', 'slug' => 'ai-machine-learning'],
            ['name' => 'Cryptocurrency', 'slug' => 'cryptocurrency'],
            ['name' => 'Startup', 'slug' => 'startup'],
            ['name' => 'Innovation', 'slug' => 'innovation'],
            ['name' => 'Social Media', 'slug' => 'social-media'],
            ['name' => 'Cybersecurity', 'slug' => 'cybersecurity'],
            ['name' => 'Space Exploration', 'slug' => 'space-exploration'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
