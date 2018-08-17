<?php

namespace Spatie\DevelopmentDashboard\Collectors;

class ExceptionCollector extends Collector
{
    protected $name = 'exception';

    public function collectedData(): array
    {
        if (is_null($this->exception)) {
            return [];
        }

        return [
            'code' => $this->exception->getCode(),
            'message' => $this->exception->getMessage(),
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'trace' => $this->exception->getTrace(),
        ];
    }
}