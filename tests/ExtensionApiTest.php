<?php

namespace Simotel\Laravel\Tests;

use Simotel\Laravel\Facade\Simotel;

class ExtensionApiTest extends TestCase
{
    public function setConfig()
    {
        $extensionApi = [
            'apps' => [
                '*' => FooExtensionApi::class,
            ],
        ];

        $config = config("simotel-laravel");
        $config["extensionApi"] = $extensionApi;
        Simotel::setConfig($config);
    }

    public function testResponse()
    {
        $this->setConfig();

        $appData = [
            'app_name' => 'fooApp',
            'data' => '1',
        ];

        $response = Simotel::extensionApi($appData);

        $this->assertJson($response->toJson());

        $response = $response->toArray();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('extension', $response);
        $this->assertEquals('999', $response["extension"]);
        $this->assertArrayHasKey('ok', $response);
    }

}

class FooExtensionApi
{
    public function fooApp()
    {
        return "999";
    }
}

