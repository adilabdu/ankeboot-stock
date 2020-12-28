<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Book extends Model
{
    use CrudTrait;

    protected $guarded = [];

    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function balance() {

        $balance = 0;
        $stocks = $this->stocks;

        if($stocks) {
            foreach ($stocks as $stock) {
                $balance += $stock->balance();
            }
        }

        return $balance;
    }
}
