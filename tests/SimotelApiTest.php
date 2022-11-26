<?php

namespace Simotel\Laravel\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Simotel\Laravel\Facade\Simotel;

class SimotelApiTest extends TestCase
{
    public function testAllMethods()
    {
        $res = Simotel::connect("pbx/users/search",[],$this->createHttpClient());
        $this->assertTrue($res->isOk());
        $this->assertEquals(200,$res->getStatusCode());
        $this->assertTrue($res->isSuccess());
    }

    

    public function createHttpClient($success=true,$message="message",$data=[])
    {
        $res = json_encode(['success' => $success, 'message' => $message, 'data' =>$data]);
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $res),
        ]);

        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }
}
