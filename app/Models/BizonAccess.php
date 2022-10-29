<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BizonAccess extends Model
{
    protected $fillable = [
        'domain_amo',
        'login',
        'password',
        'api_token',
    ];
}
