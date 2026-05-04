<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameEntry extends Model
{
    protected $fillable = [
        'agent_id',
        'baji_id',
        'game_number',
        'amount',
        'entry_user_id'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function baji()
    {
        return $this->belongsTo(Baji::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'entry_user_id');
    }
}