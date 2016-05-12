<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $fillable = ['customer_id','amount','invoice_id','sale_id'];
}
