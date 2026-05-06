<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'baji_id',
        'result_date',
        'patti',
        'single',
        'patti_win_amount',
        'single_win_amount',
        'cp_win_amount',
        'patti_win_count',
        'single_win_count',
        'cp_win_count',
        'total_entries',
        'total_collection',
        'total_liability',
        'profit_loss',
        'declared_by',
    ];

    protected $casts = [
        'result_date' => 'date',
    ];

    public function baji()
    {
        return $this->belongsTo(Baji::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'declared_by');
    }
}