<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $table = 'webhook';

    protected $fillable = ['payload'];
}
