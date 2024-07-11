<?php
namespace App\Http\Traits;

use Carbon\Carbon;

trait ReservationScopes
{
    public function scopeEndingIn24Hours($query)
    {
        $now = Carbon::now();
        $endIn24Hours = $now->copy()->addDay();

        return $query->where('end_date', '>=', $now)
                     ->where('end_date', '<=', $endIn24Hours);
    }
}
