<?php

namespace LaracraftTech\LaravelDateScopes\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaracraftTech\LaravelDateScopes\Tests\Models\Transaction;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'col1' => fake()->sentence(),
            'col2' => fake()->randomNumber(),
            'created_at' => fake()->date(),
        ];
    }
}
