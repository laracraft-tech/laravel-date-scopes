<?php

namespace LaracraftTech\LaravelDateScopes\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaracraftTech\LaravelDateScopes\DateScopes;

class CustomTransaction extends Model
{
    use HasFactory, DateScopes;

    public $timestamps = false;

    const CREATED_AT = 'custom_created_at';
}
