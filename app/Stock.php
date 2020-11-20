<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int received_amount
 * @property int issued_amount
 */
class Stock extends Model
{
    public function balance() {
        return $this->received_amount - $this->issued_amount;
    }
}
