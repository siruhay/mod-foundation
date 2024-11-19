<?php

namespace ModuleFoundation\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->call('module:migrate', ['module' => 'Foundation']);

        $this->call(FoundationBaseSeeder::class);
        $this->call(FoundationDataSeeder::class);
        $this->call(FoundationUserSeeder::class);
    }
}
