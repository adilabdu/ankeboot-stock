<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Book extends Model
{
    use CrudTrait;

    protected $guarded = [];

    public function stock() {
        return $this->hasMany(Stock::class);
    }
}
