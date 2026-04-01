<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $guarded = ['id'];

    protected function qty(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => abs($value),
        );
    }
}
