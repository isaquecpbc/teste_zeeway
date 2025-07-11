<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
        	'name'         => 'Admin Master',
        	'email'        => 'admin@adminstradores.adm',
            'admin' => true,
        	'password'     => bcrypt('Admin@adm'),
        ]);
        \App\Models\User::create([
            'name'         => 'BoJack Horseman',
            'email'        => 'bojack@horse.men',
            'password'     => bcrypt('Bo@Jack'),
        ]);
        \App\Models\User::create([
            'name'         => 'Anakin Skywalker',
            'email'        => 'bogan@imperio.tatooine',
            'password'     => bcrypt('Darth@Vader'),
        ]);
    }
}
