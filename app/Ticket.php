<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  protected $fillable = [
     'description',
     //'status_id'
     //'tag_id',
     'user_id',
 ];
}
