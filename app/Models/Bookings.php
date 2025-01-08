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

    static public function getList(array $filters): array
    {
        $result = Bookings::select('booking_id', 'hotel_id', 'customer_name', 'customer_contact', 'chekin_time', 'checkout_time', 'created_at', 'updated_at')
            ->when(!empty($filters['booking_id']), function ($query) use ($filters) {
                return $query->where('booking_id', $filters['booking_id']);
            })
            ->when(!empty($filters['hotel_id']), function ($query) use ($filters) {
                return $query->where('hotel_id', $filters['hotel_id']);
            })
            ->when(!empty($filters['customer_name']), function ($query) use ($filters) {
                return $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
            })
            ->when(!empty($filters['customer_contact']), function ($query) use ($filters) {
                return $query->where('customer_contact', 'like', '%' . $filters['customer_contact'] . '%');
            })
            ->when(!empty($filters['chekin_time']), function ($query) use ($filters) {
                return $query->where('chekin_time', '>=', $filters['chekin_time']);
            })
            ->when(!empty($filters['checkout_time']), function ($query) use ($filters) {
                return $query->where('checkout_time', '<=', $filters['checkout_time']);
            })
            ->with('hotel')
            ->get()
            ->toArray();

        return $result;
    }
}
