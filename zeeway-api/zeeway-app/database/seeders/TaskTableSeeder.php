<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = \App\Models\User::where('email', 'admin@adminstradores.adm')->first();
        $user2 = \App\Models\User::where('email', 'bojack@horse.men')->first();
        $user3 = \App\Models\User::where('email', 'bogan@imperio.tatooine')->first();

        \App\Models\Task::create([
            'user_id' => $user1->id,
            'title' => 'Take the Teste Técnico',
            'description' => 'take the Technical Test - PHP + Laravel Developer to advance in the PHP Developer process at Zeeway',
            'status' => 'done',
            'due_date' => '2025-07-11',
        ]);

        \App\Models\Task::create([
            'user_id' => $user2->id,
            'title' => 'Return to Hollywood',
            'description' => 'Return to Hollywood and regain his career and dignity',
            'status' => 'in_progress',
        ]);

        \App\Models\Task::create([
            'user_id' => $user3->id,
            'title' => 'Build the Death Star II',
            'description' => 'Building the second Death Star, also referred to as Death Star II, was a battle station with enough power to destroy an entire planet.',
            'status' => 'done',
            'due_date' => '1983-10-06',
        ]);
    }
}