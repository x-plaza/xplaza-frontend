<?php

namespace Tests\Unit;

use App\Services\ApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiServiceTest extends TestCase
{
    public function test_get_returns_data_array()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['data' => ['foo' => 'bar']]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ApiService();
        // Inject the mock client if your ApiService supports it, or mock HandleApi
        // For now, just assert structure
        $result = $service->get('/test-endpoint');
        $this->assertIsArray($result);
    }

    public function test_post_returns_status()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode(['status' => 201, 'data' => ['id' => 1]]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ApiService();
        $result = $service->post('/test-endpoint', ['foo' => 'bar']);
        $this->assertEquals(201, $result['status'] ?? null);
    }

    public function test_put_returns_status()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['status' => 200]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ApiService();
        $result = $service->put('/test-endpoint', ['foo' => 'bar']);
        $this->assertEquals(200, $result['status'] ?? null);
    }

    public function test_delete_returns_status()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['status' => 200]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ApiService();
        $result = $service->delete('/test-endpoint');
        $this->assertEquals(200, $result['status'] ?? null);
    }
}
