<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('Tags')->insert([
        [
          'ticket_id' => '1',
          'tag_id' => '1',
        ],
        [
          'ticket_id' => '1',
          'tag_id' => '2',
        ],
        [
          'ticket_id' => '2',
          'tag_id' => '3',
        ],
        [
          'ticket_id' => '2',
          'tag_id' => '4',
        ],
        [
          'ticket_id' => '3',
          'tag_id' => '1',
        ],
        [
          'ticket_id' => '3',
          'tag_id' => '2',
        ],
        [
          'ticket_id' => '4',
          'tag_id' => '3',
        ],
        [
          'ticket_id' => '4',
          'tag_id' => '4',
        ],
      ]);
    }
}
