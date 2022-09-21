<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announce extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'origin',
        'destination',
        'date',
        'weight',
        'reserved',
        'expired',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'weight' => 'integer',
    ];

    /**
     * Get the user that owns the Announce
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include origin announce.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $origin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrigin($query, $origin)
    {
        $query->when($origin, function ($query) use ($origin) {
            $query->where('origin', $origin);
        });
    }

    /**
     * Scope a query to only include destination announce.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $destination
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDestination($query, $destination)
    {
        $query->when($destination, function ($query) use ($destination) {
            $query->where('destination', $destination);
        });
    }

    /**
     * Scope a query to only include announce in date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDate($query, $date)
    {
        $query->when($date, function ($query) use ($date) {
            $query->whereDate('date', '<=', date('Y-m-d', strtotime($date)));
        });
    }

    /**
     * Scope a query to only include expired announce.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        $query->where('reserved', true)
            ->orWhereDate('date', '<=', date('Y-m-d H:i:s'));
    }

    /**
     * Scope a query to only include non expired announce.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        $query->where('reserved', false)
            ->WhereDate('date', '>', date('Y-m-d H:i:s'));
    }
}
