<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    //
    protected $table = 'daily_summaries';

    protected $fillable = ['user_id', 'team_id', 'body'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function team()
    {
        return $this->belongsTo('\App\User');
    }
}
