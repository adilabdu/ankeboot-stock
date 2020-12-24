<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function stock() {
        return $this->hasMany(Stock::class);
    }
}
