<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Book extends Model
{
    use CrudTrait;

    protected $guarded = [];

//    protected $identifiableAttribute = 'author';

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

    public function meanPrice() {

        $meanPrice = 0;
        $sum = 0;
        $total = 0;

        if($this->stocks->count() > 0) {
            foreach ($this->stocks as $stock) {
                $sum += ($stock->cost_price * $stock->received_amount);
                $total += $stock->received_amount;
            }

            if($total > 0) {
                $meanPrice = round($sum / $total, 2);
            }

        } else {
            $meanPrice = 'N/A';
        }

        return $meanPrice;
    }

    public function identifiableAttribute()
    {
        return 'name';
    }
}
