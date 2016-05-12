<?php

namespace App;

use App\Traits\HashidsEncode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Customer
 *
 * @property integer $id
 * @property string $name
 * @property string $external_card_id
 * @property string $card_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereExternalCardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereCardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HashidsEncode;
    use SoftDeletes;
    
    protected $fillable = ['name','booking_id','room_id'];

    protected $dates = ['deleted_at'];
    
    public function room()
    {
        return $this->belongsTo('\App\Room');
    }

    public function billables()
    {
        return $this->hasMany('\App\Billable');
    }
}
