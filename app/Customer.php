<?php

namespace App;

use App\Traits\HashidsEncode;
use Illuminate\Database\Eloquent\Model;


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
    
    protected $fillable = ['name','external_card_id','external_customer_id'];
}
