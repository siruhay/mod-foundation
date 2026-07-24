<?php

namespace Module\Foundation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Foundation\Models\FoundationBiodata;
use Module\Foundation\Models\FoundationCommunity;

class FoundationBiodataFactory extends Factory
{
    protected $model = FoundationBiodata::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->numerify('################'),
            'kind' => 'ASN',
            'type' => 'LKD',
            'workunitable_type' => FoundationCommunity::class,
            'workunitable_id' => FoundationCommunity::factory(),
        ];
    }

    public function type(string $type): static
    {
        return $this->state(fn () => ['type' => $type]);
    }
}
