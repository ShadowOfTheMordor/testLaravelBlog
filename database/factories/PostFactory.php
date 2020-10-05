<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title=$this->faker->realText(rand(10,40));
        $short_title=\Illuminate\Support\Str::length($title)>30 ? mb_substr($title, 0, 30)."..." : $title;
        $created_at=$this->faker->dateTimeBetween("-30 days", "-1 days");
        
        return [
            'title' => $title,
            'short_title' => $short_title,
            'author_id' => rand(1,4),//четыре рандомных пользователя. в реальных проектах сложнее
            'description' => $this->faker->realText(rand(100,500)),
            'created_at' =>$created_at,
            'updated_at' =>$created_at,
        ];
    }
}
