<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_type_id',
        'code',
        'floorNumber',
        'description',
        'images',
        'status',
        'price',
        
    ];
    protected $casts = [
        'images' => 'array',
    ];
    public function getImagesAttribute($value){
        return json_decode($value, true);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
