<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class OrderHistory extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'symbol',
        'asset',
        'income_type',
        'income',
        'time',
        'info',
        'tran_id',
        'trade_id',
    ];

    protected $hidden = [
        
    ];
}
