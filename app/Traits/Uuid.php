<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait Uuid
{

    use HasUuids;

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->uuid = (string)Str::uuid();
    //     });
    // }
}
