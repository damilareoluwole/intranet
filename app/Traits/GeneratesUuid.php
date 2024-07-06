<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUuid
{
    protected static function bootGeneratesUuid()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}