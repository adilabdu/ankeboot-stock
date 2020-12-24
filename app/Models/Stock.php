<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Stock extends Model
{
    use CrudTrait;

    public function books() {
        return $this->belongsTo(Book::class);
    }

    public function balance() {
        return $this->received_amount - $this->issued_amount;
    }
}
