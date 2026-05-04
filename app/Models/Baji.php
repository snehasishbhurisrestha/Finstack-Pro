<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Baji extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'status'
    ];

    public function entries()
    {
        return $this->hasMany(GameEntry::class);
    }

    public static function active()
    {
        $now = Carbon::now()->format('H:i:s');

        $bajis = self::where('status', 1)->get();

        foreach ($bajis as $baji) {

            $start = $baji->start_time;
            $end   = $baji->end_time;

            // normal slot
            if ($start <= $end) {
                if ($now >= $start && $now <= $end) {
                    return $baji;
                }
            }
            // midnight crossing
            else {
                if ($now >= $start || $now <= $end) {
                    return $baji;
                }
            }
        }

        return null;
    }
}