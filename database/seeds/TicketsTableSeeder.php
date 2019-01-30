<?php

use Illuminate\Database\Seeder;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      DB::table('tickets')->insert([
        [
          'description' => 'Ik heb een probleem met PHP!',
          'status_id' => '1',
          'user_id' => '1',
          'created_at' => '2019-01-30 19:51:10',
        ],
        [
          'description' => 'Ik heb een probleem met JS en HTML!',
          'status_id' => '1',
          'user_id' => '2',
          'created_at' => '2019-01-30 19:52:10',
        ],
        [
          'description' => 'Ik heb een probleem met mijn browser!',
          'status_id' => '1',
          'user_id' => '2',
          'created_at' => '2019-01-30 19:54:10',
        ],
        [
          'description' => 'Ik heb een probleem met een bug van php!',
          'status_id' => '1',
          'user_id' => '1',
          'created_at' => '2019-01-30 19:56:10',
        ],
      ]);
    }
}
