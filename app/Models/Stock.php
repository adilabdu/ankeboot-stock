<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Stock extends Model
{
    use CrudTrait;
    protected $guarded = [];

    public function book() {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function balance() {
        return $this->received_amount - $this->issued_amount;
    }
}
