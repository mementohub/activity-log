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
        $url = $this->getEndpoint('/api/logs');

        try {
            $response = $request_service->post($url, $this->data/*, $this->jwt*/);
        } catch (\Exception $e) {
            logger()->error('Error while logging an activity.');
            return false;
        }

        if($response->getStatusCode() !== 200)
            return false;
    }

    public function getEndpoint(string $path)
    {
        $host = env('ENDPOINT_INTERNAL_SERVICES_ACTIVITY_LOG');

        if (! $host)
            throw new \Exception('Activity log service endpoint is missing.');

        return $host . $path;
    }
}
