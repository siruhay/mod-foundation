<?php

namespace Module\Foundation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Models\FoundationCommunitymap;
use Module\Foundation\Models\FoundationRegency;
use Module\Foundation\Models\FoundationSubdistrict;
use Module\Foundation\Models\FoundationVillage;
use Module\Foundation\Models\FoundationWorkunit;

class FoundationCommunityFactory extends Factory
{
    protected $model = FoundationCommunity::class;

    public function definition(): array
    {
        return [
            'name' => 'LKD ' . fake()->unique()->company(),
            'slug' => (string) str()->uuid(),
            'communitymap_id' => FoundationCommunitymap::inRandomOrder()->value('id'),
            'workunit_id' => FoundationWorkunit::inRandomOrder()->value('id'),
            'village_id' => FoundationVillage::inRandomOrder()->value('id'),
            'subdistrict_id' => FoundationSubdistrict::inRandomOrder()->value('id'),
            'regency_id' => FoundationRegency::inRandomOrder()->value('id'),
            'scopes' => json_encode(['HEALTH']),
        ];
    }
}
