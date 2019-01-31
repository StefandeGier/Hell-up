<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ticket extends Model
{
  protected $fillable = [
     'description',
     'user_id',
  ];
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function status()
  {
    return $this->belongsTo('App\Status');
  }

  public function tag()
  {
    return $this->hasMany('App\Tag', 'ticket_id');
  }


}
