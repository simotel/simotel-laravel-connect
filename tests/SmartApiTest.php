<?php

namespace Simotel\Laravel\Tests;

use Simotel\SmartApi\Commands as SmartApiCommands;
use Illuminate\Support\Facades\Config;
use Simotel\Laravel\Facade\Simotel;

class SmartApiTest extends TestCase
{

    public function setConfig()
    {
        $smartApiConfig = [
            'apps' => [
                'fooApp' => FooSmartApi::class,
                '*' => RestOfApps::class,
            ],
        ];

        $config = config("simotel-laravel");
        $config["smartApi"] = $smartApiConfig;
        Simotel::setConfig($config);
    }


    public function testResponse()
    {

        $this->setConfig();

        $appData = [
            'app_name' => 'fooApp',
            'data' => 'foo',
        ];

        $response = Simotel::smartApi($appData);

        $this->assertJson($response->toJson());

        $response = $response->toArray();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('commands', $response);
        $this->assertArrayHasKey('ok', $response);
    }

    public function testNomakeOkResponse()
    {
        $this->setConfig();

        $appData = [
            'app_name' => 'barApp',
        ];

        $response = Simotel::smartApi($appData)->toArray();
        $this->assertEquals(['ok' => 0], $response);
    }

    public function testPlayAnnouncementCommand()
    {
        $this->setConfig();

        $appData = [
            'app_name' => 'playAnnounceApp',
            'data' => 'welcome',
        ];

        $response = Simotel::smartApi($appData)->toArray();
        $this->assertEquals(['ok' => 1, 'commands' => "PlayAnnouncement('welcome')"], $response);
    }
}

class FooSmartApi
{
    use SmartApiCommands;

    public function fooApp()
    {
        $this->cmdExit(1);

        return $this->makeOkResponse();
    }
}

class RestOfApps
{
    use SmartApiCommands;

    public function barApp()
    {
        return $this->makeNokResponse();
    }

    public function playAnnounceApp($appData)
    {
        $this->cmdPlayAnnouncement($appData['data']);

        return $this->makeOkResponse();
    }
}
