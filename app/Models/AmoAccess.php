<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmoAccess extends Model
{
    protected $fillable = [
        'domain',
        'client_id',
        'client_secret',
        'redirect_url',
        'code',
    ];
}
