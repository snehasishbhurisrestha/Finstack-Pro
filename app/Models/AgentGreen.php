<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentGreen extends Model
{
    protected $fillable = [
        'agent_id',
        'baji_id',
        'is_win',
    ];

    protected $casts = [
        'is_win' => 'boolean',
    ];

    /**
     * Agent relationship
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Baji relationship
     */
    public function baji()
    {
        return $this->belongsTo(Baji::class);
    }
}