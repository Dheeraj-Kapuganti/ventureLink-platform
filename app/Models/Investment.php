<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'investor_id',
        'amount',
        'equity_percentage',
        'status',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class, 'startup_id');
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }
}
