<?php

namespace Simotel\Laravel\Tests;

use Simotel\Laravel\Facade\Simotel;

class IvrApiTest extends TestCase
{
    public function setConfig()
    {
        $ivrApi = [
            'apps' => [
                'fooApp' => FooIvrApi::class,
            ],
        ];

        $config = config("simotel-laravel");
        $config["ivrApi"] = $ivrApi;
        Simotel::setConfig($config);
    }

    public function testResponse()
    {

        $this->setConfig();

        $appData = [
            'app_name' => 'fooApp',
            'data'     => '1',
        ];

        $response = Simotel::ivrApi($appData);

        $this->assertJson($response->toJson());

        $response = $response->toArray();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('case', $response);
        $this->assertEquals('route 1', $response["case"]);
        $this->assertArrayHasKey('ok', $response);
    }
}

class FooIvrApi
{
    public function fooApp()
    {
        return "route 1";
    }
}

