<?php

namespace iMemento\ActivityLog\Tests;

use GuzzleHttp\Psr7\Response;
use iMemento\ActivityLog\ActivityLog;
use iMemento\ActivityLog\RequestService;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->mock(RequestService::class, function ($mock) {
            $mock->shouldReceive('post')
                ->once()
                ->with('fake_endpoint/api/logs', \Mockery::any())
                ->andReturn(new Response());
        });

        $service = new ActivityLog();

        $service->log('some message');
    }
}
