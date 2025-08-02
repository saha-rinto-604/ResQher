<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VictimLocation extends Model
{
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'last_updated_at'
    ];
}
