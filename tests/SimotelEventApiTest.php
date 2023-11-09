<?php

namespace Simotel\Laravel\Tests;


use Illuminate\Support\Facades\Event;
use Simotel\Laravel\Facade\Simotel;

class SimotelEventApiTest extends TestCase
{

    public function testCdr()
    {
        Event::fake();

        $events = [
            "Cdr", "NewState", "IncomingCall", "OutgoingCall", "Transfer", "ExtenAdded", "ExtenRemoved",
            "IncomingFax", "IncomingFax", "CdrQueue", "VoiceMail", "VoiceMailEmail", "Survey", "Ping"
        ];

        foreach ($events as $event) {
            Simotel::eventApi()->dispatch($event, []);
            $eventClassName = "Simotel\Laravel\Events\SimotelEvent" . $event;
            Event::assertDispatched($eventClassName);
        }
    }

}
