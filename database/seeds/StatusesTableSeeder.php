<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('Statuses')->insert([
        [
          'name' => 'Pending',
        ],
        [
          'name' => 'Busy',
        ],
        [
          'name' => 'Archive',
        ],
        [
          'name' => 'Deleted',
        ],
      ]);
    }
}
