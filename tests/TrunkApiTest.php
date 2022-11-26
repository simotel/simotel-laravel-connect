<?php

namespace Simotel\Laravel\Tests;


use Simotel\Laravel\Facade\Simotel;

class TrunkApiTest extends TestCase
{

    public function setConfig()
    {
        $trunkApiConfig = [
            'apps' => [
                '*' => FooTrunkApi::class,
            ],
        ];

        $config = config("simotel-laravel");
        $config["trunkApi"] = $trunkApiConfig;
        Simotel::setConfig($config);
    }

    public function testResponse()
    {
        $this->setConfig();

        $appData = [
            'app_name' => 'fooApp',
            'data'     => '1',
        ];

        $response = Simotel::trunkApi($appData);

        $this->assertJson($response->toJson());

        $response = $response->toArray();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('trunk', $response);
        $this->assertEquals('999', $response["extension"]);
        $this->assertArrayHasKey('ok', $response);
    }

}

class FooTrunkApi
{
    public function fooApp()
    {
        return [
            "trunk"=>"test trunk",
            "extension"=>"999",
            "call_limit"=>"500",
        ];
    }
}

