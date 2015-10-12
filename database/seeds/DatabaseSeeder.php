<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run all the database seeders
     *
     * @return void
     */
    public function run()
    {
        $this->call(AgentSeeder::class);
        $this->command->info('Agents seeded');
    }
}