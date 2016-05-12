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
    public function getHashIdAttribute($value)
    {
        return Hashids::encode($this->id);
    }

}