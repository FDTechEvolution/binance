<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class UsdmPrice extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'jsondata',
        'time'
    ];

    protected $hidden = [];
}
