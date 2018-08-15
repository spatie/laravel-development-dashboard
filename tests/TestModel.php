<?php

namespace Spatie\DevelopmentDashboard\Tests;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $guarded = [];

    public $timestamps = false;
}