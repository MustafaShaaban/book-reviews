<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function($book){
            $randomReviews = random_int(5, 30);

            Review::factory($randomReviews)->good()->for($book)->create();

        });

        Book::factory(33)->create()->each(function($book){
            $randomReviews = random_int(5, 30);

            Review::factory()->count($randomReviews)->average()->for($book)->create();

        });

        Book::factory(34)->create()->each(function($book){
            $randomReviews = random_int(5, 30);

            Review::factory()->count($randomReviews)->bad()->for($book)->create();

        });
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
