<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $attributes = [
        'status' => 'Sent'
    ];

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the event associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the service_pricing that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service_pricing()
    {
        return $this->belongsTo(ServicePricing::class);
    }

    /**
     * Get the order_quotation that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_quotation()
    {
        return $this->belongsTo(OrderQuotations::class);
    }

    /**
     * Get the payment associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the review associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all of the messages for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public function statusWidth()
    {
      switch ($this->status) {
         case 'Sent':
            return '20';
            break;
         case 'Received':
            return '20';
            break;
         case 'Paid':
            return '20';
            break;
         case 'Delivered':
            return '20';
            break;
         case 'Completed':
            return '20';
            break;
         case 'Disputed':
            return '20';
            break;
         case 'Declined':
            return '50';
            break;
         case 'Cancelled':
            return '33.33';
            break;
         default:
         return '20';
            break;
      }
    }
}
