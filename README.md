# simotel-laravel-connect

> ### PHP Package
> connect to simotel with simotel-php-connect:
> [nasimtelecom/simotel-laravel-connect](https://github.com/nasimtelecom/simotel-laravel-connect)

Keep connected with Simotel by Laravel. Simotel is a wonderful call center software with huge abilities.
visit **[simotel](https://simotel.com/)** documents here: [doc.mysup.ir](https://doc.mysup.ir/)

With this package you can easly connect to **[simotel](https://simotel.com/)** server by laravel and do somethings amazing.



- [Install](#install)
- [How to use](#how-to-use)
    - [Simotel API](#simotel-api)
    - [Simotel Event API](#simotel-event-api)
    - [Simotel Smart API](#simotel-smart-api)
    - [Simotel Trunk API](#simotel-trunk-api)
    - [Simotel Extension API](#simotel-extension-api)
    - [Simotel Ivr API](#simotel-ivr-api)
- [Change log](#change-log)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Install

Use composer to install and autoload the package:
```
composer require simotel/simotel-laravel-connect
```
#### Publish config
Use artisan command to publish simotel connect config file in laravel config folder:

```
php artisan vendor:publish --provider="NasimTelecom\Simotel\Laravel\SimotelLaravelServiceProvider"
```

```php
// config/laravel-simotel.php

[
    'smartApi' => [
        'apps' => [
            '*' => "\YourApp\SmartApiAppClass",
        ],
    ],
    'ivrApi' => [
        'apps' => [
            '*' => "\YourApp\IvrApiAppClass",
        ],
    ],
    'trunkApi' => [
        'apps' => [
            '*' => "\YourApp\TrunkApiApp",
        ],
    ],
    'extensionApi' => [
        'apps' => [
            '*' => "\YourApp\ExtensionApiAppClass",
        ],
    ],
    'simotelApi' => [
        'server_address' => 'http://yourSimotelServer/api/v4',
        'api_auth' => 'basic',  // simotel api authentication: basic,token,both
        'api_user' => 'apiUser',
        'api_pass' => 'apiPass',
        'api_key' => 'apiToken',
    ],
];
```
## How to use

### Simotel API
Simotel API helps you to connect to simotel server and manage simotel users, queues, trunks, announcements, get reports, send faxes [and more](https://doc.mysup.ir/docs/api/v4/callcenter_api/SimoTelAPI/settings).



#### Connect to Simotel API

```php
use Simotel\Laravel\Facade\Simotel;

// The data will be sent to Simotel server as request body
$data = [
    "alike"=>false,
    "conditions"=>["name"=>"200"],
];

try{
    // Sending request to simotel server
    $res = Simotel::connect("pbx/users/search",$data);
}
catch(\Exception $e){
    die($e->getMessage());
}


// Determines whether the transaction was successful or not
// In other words if the response status code is 
// between 200~299 then isOk() will return true 
if(!$res->isOk())
    die("There is a problem");

// Or you can get response status code
$statusCode = $res->getStatusCode();

// Simotel will return a json response,
// to cast it to array use toArray() method
// it will be an array like this:
// [
//      "success" => true/false, 
//      "message" => "Simotel Error Message"
//      "data"    =>  [response data array]    
// ]
// success: determine wether transaction by simotel is ok or not
// message: this is simotel response message
// that tell us why transactoion did not completed
$res->toArray();

// Simotel Success is true or false
if(!$res->isSuccess())
    // Get Simotel message if isSuccess()==false
    die($res->getMessage());

// Get Simotel response data array
$users = $res->getData();

```
### Simotel Event Api

You can use  our predefined SimotelEvents in EventServiceProvider to associate with listeners.

Here is the list of Simotel Event classes that you can use:

| Event Name    | Simotel Event Class                                           |
|     ---       |        ---                                                    |
| Cdr           | Nasim\Simotel\Laravel\Events\SimotelEventCdr::class           |
| CdrQueue      | Nasim\Simotel\Laravel\Events\SimotelEventCdrQueue::class      |
| ExtenAdded    | Nasim\Simotel\Laravel\Events\SimotelEventExtenAdded::class    |
| ExtenRemoved  | Nasim\Simotel\Laravel\Events\SimotelEventExtenRemoved::class  |
| IncomingCall  | Nasim\Simotel\Laravel\Events\SimotelEventIncomingCall::class  |
| IncomingFax   | Nasim\Simotel\Laravel\Events\SimotelEventIncomingFax::class   |
| NewState      | Nasim\Simotel\Laravel\Events\SimotelEventNewState::class      |
| OutgoingCall  | Nasim\Simotel\Laravel\Events\SimotelEventOutgoingCall::class  |
| Survey        | Nasim\Simotel\Laravel\Events\SimotelEventSurvey::class        |
| Transfer      | Nasim\Simotel\Laravel\Events\SimotelEventTransfer::class      |
| VoiceMail     | Nasim\Simotel\Laravel\Events\SimotelEventVoiceMail::class     |
| VoiceMailEmail| Nasim\Simotel\Laravel\Events\SimotelEventVoiceMailEmail::class|

Now, you must dispatch Simotel events in router:
```php
// routes/api.php

Route::get("/eventApi",function(){
    Simotel::eventApi()->dispatch(request()->all());
})
```
### Simotel Smart API
> We recommend you to study [Simotel SmartApi documents](https://doc.mysup.ir/docs/api/callcenter_api/APIComponents/smart_api) first.

#### 1. create smartApp classes and methods that called by smart api apps

```php
namespace \App\SimotelSmartApps;

use Simotel\SmartApi\Commands;

class PlayWelcomeAnnounce
{
    use Commands;
    
    public function playAnnounceApp($appData)
    {
        $this->cmdPlayAnnouncement("announcement file name");
        return $this->okResponse();
        // return: {'ok':1,'commands':'PlayAnnouncement('announcement file name')'}
    }
}
```
```php
namespace \App\SimotelSmartApps;

class RestOfApps
{
    use SmartApiCommands;
    
    public function sayClock($appData)
    {
        $this->cmdSayClock("14:00");
        return $this->makeOkResponse();
        // return: {'ok':1,'commands':'SayClock("14:00")'} 
    }

    public function interactiveApp($appData)
    {
        if($appData["data"]=="1")
            return $this->makeOkResponse();
            // return: {'ok':1}

        if($appData["data"]=="2")
            return $this->makeNokResponse();
            // return: {'ok':0}
    }
}

```
> Don't forget to `use` [ Simotel\SmartApi\Commands](https://github.com/nasimtelecom/simotel-php-connect/blob/main/src/SmartApi/Commands.php) trait in your class.


### 2. customize simotel config
```php
// config/laravel-simotel.php

 'smartApi' => [
        'apps' => [
            'playAnnounce' => \App\SimotelSmartApps\PlayWelcomeAnnounce::class,
            '*' => \App\SimotelSmartApps\RestOfApps::class,
        ],
    ],

```

#### 3. handle received request from simotel smart api

```php
Route::get("smartApi",function(){
    Simotel::smartApi()->call(request()->all());
})
$config = Simotel::getDefaultConfig();
$config["smartApi"]["apps"] = [
  'playWelcomeMessage' => PlayWelcomeMessage::class,
  '*' => RestOfApps::class,
];

// place this codes where you want grab income requests
// from simotel smartApi calls     
$simotel = new Simotel($config);
$appData = $_POST["app_data"];
$jsonResponse = $simotel->smartApi($appData)->toJson();

header('Content-Type: application/json; charset=utf-8');
echo $jsonResponse;

/*
 if app_name='playAnnounceApp' 
	 jsonResponse = {'ok':1,'commands':'PlayAnnouncement('announcement file name')'}

 if app_name='sayClock' 
	 jsonResponse = {'ok':1,'commands':'SayClock("14:00")'}

 if app_name='interactiveApp' 
	 if data=1 
		 jsonResponse = {'ok':1}
	 if data=2 
		 jsonResponse = {'ok':0}
*/
```

there are commands that you can use in your SmartApp classes:

```php
cmdPlayAnnouncement($announceFilename);
cmdPlayback($announceFilename);
cmdExit($exit);
cmdGetData($announceFilename, $timeout, $digitsCount);
cmdSayDigit($number);
cmdSayNumber($number);
cmdSayClock($clock);
cmdSayDate($date,$calender);
cmdSayDuration($duration);
cmdSetExten($exten, $clearUserData = true);
cmdSetLimitOnCall($seconds);
cmdClearUserData();
cmdMusicOnHold();
```

### Simotel Trunk API
> We recommend you to study [Simotel Trunk API documents](https://doc.mysup.ir/docs/api/callcenter_api/APIComponents/trunk_api) first.

#### 1. create TrunkApp classe and methods

```php

class SelectTrunk
{
    public function selectTrunk($appData)
    {
        if(/* some conditions */)
            return [
                "trunk" => "trunk1",
                "extension" => "extension1",
                "call_limit" => "300"
            ];
        
        //else
        return [
            "trunk" => "trunk2",
            "extension" => "extension2",
            "call_limit" => "400"
        ];
    }
}


```

#### 2. handle received request from Simotel Trunk API

```php
$config = Simotel::getDefaultConfig();
$config["trunkApi"]["apps"] = [
  'selectTrunk' => SelectTrunk::class,
];

// place this codes where you want grab income requests
// from simotel smartApi calls     
$simotel = new Simotel($config);
$appData = $_POST["app_data"];
$jsonResponse = $simotel->trunkApi($appData)->toJson();

header('Content-Type: application/json; charset=utf-8');
echo $jsonResponse;

/*
    if some conditions then 
		 jsonResponse = {
            "ok": "1",             
            "trunk": "trunk1",
            "extension": "extension1",
            "call_limit": "300"
        }
	 else 
		jsonResponse = {
            "ok": "1",             
            "trunk": "trunk2",
            "extension": "extension2",
            "call_limit": "400"
        }
*/
```

### Simotel Extension API
> We recommend you to study [Simotel Extension API documents](https://doc.mysup.ir/docs/api/callcenter_api/APIComponents/exten_api) first.



#### 1. create Extension API class and methods

```php

class SelectExtension
{
    public function selectExtension($appData)
    {
        if(/* some conditions */)
            return "ext1";
        
        //else
            return "ext2";
    }
}


```

#### 2. handle received request from Simotel Extension API

```php
$config = Simotel::getDefaultConfig();
$config["extensionApi"]["apps"] = [
  'selectExtension' => SelectExtension::class,
];

// place this codes where you want grab income requests
// from simotel extensionApi calls     
$simotel = new Simotel($config);
$appData = $_POST["app_data"];
$jsonResponse = $simotel->extensionApi($appData)->toJson();

header('Content-Type: application/json; charset=utf-8');
echo $jsonResponse;

/*
    if some conditions then 
		 jsonResponse = {"ok": "1", "extension": "ext1"}
	 else 
		 jsonResponse = {"ok": "1", "extension": "ext2"}
*/
```

### Simotel Ivr API
> We recommend you to study [Simotel Ivr API documents](https://doc.mysup.ir/docs/api/callcenter_api/APIComponents/ivr_api) first.


#### 1. create Ivr API class and methods

```php

class SelectIvrCase
{
    public function selectCase($appData)
    {
        if(/* some conditions */)
            return "1";
        
        //else
            return "2";
    }
}


```

#### 2. handle received request from Simotel Ivr API

```php
$config = Simotel::getDefaultConfig();
$config["ivrApi"]["apps"] = [
  'selectCase' => SelectIvrCase::class,
];

// place this codes where you want grab income requests
// from simotel ivrApi calls     
$simotel = new Simotel($config);
$appData = $_POST["app_data"];
$jsonResponse = $simotel->ivrApi($appData)->toJson();

header('Content-Type: application/json; charset=utf-8');
echo $jsonResponse;

/*
    if some conditions then 
		 jsonResponse = {"ok": "1", "case": "1"}
	 else 
		 jsonResponse = {"ok": "1", "case": "2"}
*/
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has been changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hosseinyaghmaee@gmail.com instead of using the issue tracker.

## Credits

- [Simotel][link-simotel]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/hsyir
[link-simotel]: https://simotel.com/
