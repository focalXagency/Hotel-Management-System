<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationStatusCatlog extends Model
{
    use HasFactory;
    public function reservationStatusEvents():HasMany
    {
        return $this->hasMany(ReservationStatusEvent::class);
    }
}
