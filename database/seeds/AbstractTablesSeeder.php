<?php

use Illuminate\Database\Seeder;
use App\Models\Estate\Estate;

class AbstractTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::latest()->get()->take(10);
        $estates = Estate::latest()->get()->take(20);

        // Add favorites products for the users
        $users->each( function ( $user ) use ($estates) {

            $user->favorites()->sync( $estates->take( rand(1, 10)) );
        });
    }
}
