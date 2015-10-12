<?php

use Illuminate\Database\Seeder;
use App\Agent;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agents = [
            new Agent(['extension' => 'Brodo', 'phone_number'   => '+15552483591']),
            new Agent(['extension' => 'Dagobah', 'phone_number' => '+15558675309']),
            new Agent(['extension' => 'Oober', 'phone_number'   => '+15553185602'])
        ];

        foreach ($agents as $agent) {
            $agent->save();
        }
    }
}
