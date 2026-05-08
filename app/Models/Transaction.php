<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'network', 'phone_number', 'plan_name',
        'amount', 'balance_before', 'balance_after', 'status',
        'api_response', 'reference'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
