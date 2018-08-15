<?php

namespace Spatie\DevelopmentDashboard\Tests\Support;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $guarded = [];

    public $timestamps = false;
}