<?php

namespace App\Models;

use App\Traits\Reusables\HasAbsoluteFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasAbsoluteFields;
    //
    protected $guarded = ['id'];

    protected $absoluteFields = ['cost','qty','total'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
