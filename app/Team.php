<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    public function projects()
    {
        return $this->hasMany('\App\Project')
            ->orderBy('priority');
    }

    public function users()
    {
        return $this->belongsToMany('\App\User')
            ->orderBy('name');
    }
}
