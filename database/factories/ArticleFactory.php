<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $author = User::whereIn('role', ['admin', 'doctor'])->inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(640, 480, 'articles', true),
            'articles_content' => $this->faker->paragraphs(3, true),
            'author_id' => $author ? $author->id : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
