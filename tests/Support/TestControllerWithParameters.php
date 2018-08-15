<?php

namespace Spatie\DevelopmentDashboard\Tests\Support;

class TestControllerWithParameters
{
    public function show(TestModel $testModel)
    {
        return "Test model id: {$testModel->id}";
    }
}