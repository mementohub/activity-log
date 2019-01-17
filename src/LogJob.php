<?php

namespace iMemento\ActivityLog;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    //protected $jwt;

    public function __construct(array $data/*, string $jwt = null*/)
    {
        $this->data = $data;
        //$this->jwt = $jwt;
    }

    public function handle(RequestService $request_service)
    {
        $url = env('ENDPOINT_INTERNAL_SERVICES_ACTIVITY_LOG') . '/api/logs';

        $response = $request_service->post($url, $this->data/*, $this->jwt*/);

        if($response->getStatusCode() !== 200)
            return false;
    }
}
