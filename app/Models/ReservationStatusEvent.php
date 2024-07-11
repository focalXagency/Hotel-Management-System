<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReservationStatusEvent extends Model
{
    use HasFactory;
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function reservationStatusCatalogs():BelongsTo
    {
        return $this->belongsTo(ReservationStatusCatlog::class,'reservation_status_catlog_id');
    }
}
