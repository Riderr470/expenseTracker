<?php

namespace App\Models;

use App\Traits\Reusables\HasAbsoluteFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasAbsoluteFields;
    //
    protected $guarded = ['id'];

    protected $absoluteFields = ['cost','qty','total'];

}
