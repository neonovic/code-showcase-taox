<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueprintOptionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['data'];

    protected $casts = [
        'data' => 'array'
    ];
}
