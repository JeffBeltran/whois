<?php

use App\Institution;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class BasicSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'email' => 'a@a.com',
            'password' => Hash::make('a'),
        ]);
        factory(Institution::class, 10)->create();
    }
}
