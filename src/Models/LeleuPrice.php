<?php

namespace DFM\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;

/**
 * Class LeleuPrice
 *
 * @package DFM\Shipping
 *
 * @property-read  int     $id
 * @property-read  int     $volume
 * @property-read  double  $price
 * @property-read  int     $state_id
 */
class LeleuPrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leleu_prices';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['volume', 'price', 'state_id'];

    /**
     * Get the state that owns the leleu price.
     */
    public function state()
    {
        return $this->belongsTo(CountryState::class, 'state_id');
    }
}
