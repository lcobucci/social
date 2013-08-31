social
======

Social authentication library for PHP 5.3+

## Instalation

Social should be installed using [composer](https://packagist.org/packages/lcobucci/social).

## Basic usage

1. Create a AuthClient and register the providers:

```php
// client_config.php
require_once __DIR__ . '/vendor/autoload.php'; //requires the autoloader

use Lcobucci\Social\Providers\Github;
use Lcobucci\Social\AuthClient;
use Lcobucci\Social\Providers\Facebook;

$client = new AuthClient();

$client->appendProvider(
    'github', // The provider identifier (anything you may want) 
    Github::create(
        'blablabla', // The client ID
        'blablabla', // The client secret
        'http://blablabla.com/oauth/github/callback.php'// The callback URI  (if you want...)
        ['user:email'] // The default scopes (if you want...)
    )
);

return $client
```

2. Redirect to provider

```php
// init.php
$client = require __DIR__ . '/client_config.php'; // Get your configuration
$uri = $client->getAuthorizationUri(
    'github', // The provider identifier you want to use (that you configured before)
    [], // Additional scopes (if you want...)
    uniqid() // State to be validated (if you want...)
);

header('Location: ' . $uri);
```

3. Get authenticated user information

```php
// callback.php
use Symfony\Component\HttpFoundation\Request;

$client = require __DIR__ . '/client_config.php'; // Get your configuration
$request = Request::createFromGlobals();

$user = $client->getAuthenticatedUser('github', $request->query);

var_dump($user->getToken()); // Get the access token that should be used on API requests
var_dump($user->getId()); // Get the user ID
var_dump($user->getUsername()); // Get the user login
var_dump($user->getName()); // Get the user name
var_dump($user->getEmail()); // Get the user email
var_dump($user->getAvatar()); // Get the user avatar
```