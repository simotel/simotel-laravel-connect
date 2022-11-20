# simotel-php-connect

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

$config = Simotel::getDefaultConfig();
$config["simotelApi"]= [
        'api_auth' => 'both', // simotel api authentication: basic,token,both 
        'api_user' => 'apiUser',
        'api_pass' => 'apiPass',
        'api_token' => 'apiToken',
        'server_address' => 'http://simotelServer/api/v4',
    ],


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
