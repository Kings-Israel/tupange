<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderQuotations extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'status' => 'Pending'
    ];

    /**
     * Get all of the orders for the OrderQuotations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
