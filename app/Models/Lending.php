<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'name',
        'qty',
        'date_lending',
        'is_returned',
        'condition',
        'qty_broken',
        'penalty_amount',
        'is_penalty_paid',
        'notes',
        'signature',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
