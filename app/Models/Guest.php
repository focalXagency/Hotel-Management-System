<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;
    protected $fillable = [
        'identificationNumber',  
        'name',
        'birthDate',
        'phone_number',
    ];

    protected $dates = ['birthDate'];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
