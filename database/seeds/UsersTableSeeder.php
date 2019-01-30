<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        [
          'firstname' => 'Stefan',
          'lastname' => 'de Gier',
          'student_number' => '99040150',
          'role' => '0',
          'cohort' => '2016',
          'email' => '99040150@mydavinci.com',
          'password' => bcrypt('root99'),
        ],
        [
          'firstname' => 'Jan',
          'lastname' => 'de Man',
          'student_number' => '99040156',
          'role' => '0',
          'cohort' => '2016',
          'email' => '99040156@mydavinci.com',
          'password' => bcrypt('root98'),
        ],
      ]);
    }
}
