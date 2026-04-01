<?php

namespace App\Traits\Reusables;

trait HasAbsoluteFields
{
    protected static function bootHasAbsoluteFields()
    {
        static::saving(function ($model) {
            foreach ($model->absoluteFields ?? [] as $field) {
                if (isset($model->$field)) {
                    $model->$field = abs($model->$field);
                }
            }
        });
    }
}
