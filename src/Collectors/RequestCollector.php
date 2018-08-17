<?php

namespace Spatie\DevelopmentDashboard\Collectors;

use Illuminate\Http\Request;

class RequestCollector extends Collector
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    protected $name = 'request';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collectedData(): array
    {
        return [
            'format' => $this->request->format(),
            'request_query' => $this->request->query->all(),
            'request_request' => $this->request->request->all(),
            'request_headers' => $this->request->headers->all(),
            'request_server' => $this->request->server->all(),
            'request_cookies' => $this->request->cookies->all(),
            'path_info' => $this->request->getPathInfo(),
        ];
    }
}