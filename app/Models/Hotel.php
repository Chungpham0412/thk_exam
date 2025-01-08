<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'hotel_id';

    /**
     * @var array
     */
    protected $guarded = ['hotel_id'];

    /**
     * @return BelongsTo
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * Search hotel by hotel name
     *
     * @param string $hotelName
     * @return array
     */
    static public function getHotelListByName(string $hotelName): array
    {
        $result = Hotel::where('hotel_name', 'like', "%$hotelName%")
            ->with('prefecture')
            ->get()
            ->toArray();

        return $result;
    }
    /**
     * Search hotel by hotel name
     *
     * @param string $hotelName
     * @return array
     */
    static public function getList(array $filters): array
    {
        $result = Hotel::select('hotel_id', 'hotel_name', 'prefecture_id', 'file_path', 'created_at', 'updated_at')
            ->when(!empty($filters['prefecture_id']), function ($query) use ($filters) {
                return $query->where('prefecture_id', $filters['prefecture_id']);
            })
            ->when(!empty($filters['hotel_name']), function ($query) use ($filters) {
                return $query->where('hotel_name', 'like', "%{$filters['hotel_name']}%");
            })
            ->with('prefecture')
            ->get()
            ->toArray();

        return $result;
    }

    /**
     * Override serializeDate method to customize date format
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
