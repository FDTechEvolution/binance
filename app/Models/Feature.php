<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Feature extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'coin_name',
        'type',
        'entry1',
        'entry2',
        'entry3',
        'target1',
        'target2',
        'target3',
        'stop_loss',
        'avg_price',
        'usdt_pnl',
        'status',
        'docdate',
        'description'
    ];

    protected $hidden = [];
}
