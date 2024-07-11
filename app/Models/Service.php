<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'price',
        'description',
        'img',
    ];
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class);
    }
}
