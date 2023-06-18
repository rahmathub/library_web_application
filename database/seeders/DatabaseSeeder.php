<?php

namespace Database\Seeders;

use App\Models\Member;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i=0; $i < 20; $i++){
            $database = new Member;

            $database->name = $faker->name;
            $database->gender = $faker->randomElement(['P', 'W']);
            $database->phone_number = '0822' .$faker->randomNumber(8);
            $database->address = $faker->address;
            $database->email = $faker->email;

            $database->save();
        }
    }
}
