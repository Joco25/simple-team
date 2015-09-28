<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    //
    protected $fillable = ['card_id', 'team_id', 'body', 'priority'];
}
