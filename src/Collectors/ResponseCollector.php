<?php

namespace Spatie\DevelopmentDashboard\Collectors;

class ResponseCollector extends Collector
{
    protected $name = 'response';

    public function collectedData(): array
    {
        return [
            'headers' => $this->response->headers->all(),
            'status_code' => $this->response->getStatusCode(),
        ];
    }
}
