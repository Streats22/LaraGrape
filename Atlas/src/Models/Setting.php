<?php

namespace Streats\Atlas\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'atlas_settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
