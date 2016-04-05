<?php

namespace App\Traits;

use Hashids;

trait HashidsEncode
{
    /**
     * Get the id as hashids.
     *
     * @param  $value
     * @return string
     */
    public function getIdAttribute($value)
    {
        return Hashids::encode($value);
    }

}