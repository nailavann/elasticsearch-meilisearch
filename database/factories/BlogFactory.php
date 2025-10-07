<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(4, 10));
        $publishedAt = fake()->boolean(80) ? fake()->dateTimeBetween('-2 years', 'now') : null;
        
        return [
            'title' => rtrim($title, '.'),
            'content' => fake()->paragraphs(rand(10, 30), true),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? 1,
            'author' => fake()->name(),
            'views' => fake()->numberBetween(0, 50000),
            'is_published' => $publishedAt !== null,
            'published_at' => $publishedAt,
        ];
    }
}
