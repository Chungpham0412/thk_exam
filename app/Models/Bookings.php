<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookings extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * @var array
     */
    protected $guarded = ['booking_id'];

    /**
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
}
