PHP Client for [![](https://cdn.steamgifts.com/img/favicon.ico) SteamGifts](https://www.steamgifts.com/)
=====

[![Latest Version](https://img.shields.io/github/release/marmozist/steam-gifts.svg?style=flat-square)](https://github.com/marmozist/steam-gifts/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/marmozist/steam-gifts/blob/master/LICENSE)
[![Repository Size](https://img.shields.io/github/repo-size/marmozist/steam-gifts?style=flat-square)](https://github.com/marmozist/steam-gifts)
[![Total Downloads](https://img.shields.io/packagist/dt/marmozist/steam-gifts.svg?style=flat-square)](https://packagist.org/packages/marmozist/steam-gifts)

[![Build Status](https://img.shields.io/travis/marmozist/steam-gifts/master.svg?style=flat-square)](https://travis-ci.org/marmozist/steam-gifts)
[![Coverage Status](https://img.shields.io/coveralls/github/marmozist/steam-gifts?style=flat-square)](https://coveralls.io/github/marmozist/steam-gifts?branch=master)
[![Maintainability](https://img.shields.io/codeclimate/maintainability-percentage/marmozist/steam-gifts?style=flat-square)](https://codeclimate.com/github/marmozist/steam-gifts/maintainability)
[![Dependencies](https://img.shields.io/librariesio/github/marmozist/steam-gifts?style=flat-square)](https://libraries.io/github/marmozist/steam-gifts)

## Installation
Installation via [Composer](https://getcomposer.org/):
```
composer require marmozist/steam-gifts
```

## Usage

#### Use factory to create client
```php
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\UseCase\GetUser;

$userProvider = HttpUserProviderFactory::createProvider(
    HttpClientType::Curl(), 
    CompositeUserProcessorFactory::createProcessor()
);
$giveawayProvider = HttpGiveawayProviderFactory::createProvider(
    HttpClientType::Curl(), 
    CompositeGiveawayProcessorFactory::createProcessor(new GetUser\Interactor($userProvider))
);

$client = ClientFactory::createClient($userProvider, $giveawayProvider);
```
You can to implement `UserProvider`/`GiveawayProvider` interfaces.

#### UserProvider implementations
+ [HttpUserProvider](#httpuserprovider)

##### HttpUserProvider
`HttpUserProvider` implements `UserProvider` via [HTTPlug](https://github.com/php-http/httplug).

using via **[Curl](https://github.com/php-http/curl-client)**
```
composer require php-http/curl-client
```

```php
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;

$userProvider = HttpUserProviderFactory::createProvider(
    HttpClientType::Curl(), 
    CompositeUserProcessorFactory::createProcessor()
);
````
using via **[Guzzle](https://github.com/php-http/guzzle6-adapter)**
```
composer require php-http/guzzle6-adapter
```

```php
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;

$userProvider = HttpUserProviderFactory::createProvider(
    HttpClientType::Guzzle(), 
    CompositeUserProcessorFactory::createProcessor()
);
````
using via **[Buzz](https://github.com/kriswallsmith/Buzz)**
```
composer require kriswallsmith/buzz
```

```php
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;

$userProvider = HttpUserProviderFactory::createProvider(
    HttpClientType::Buzz(), 
    CompositeUserProcessorFactory::createProcessor()
);
````
using via your implementation
```php
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;
use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @method static self Custom()
 */
class ExtendedHttpClientType extends HttpClientType
{
    const Custom = 'Custom';
}

class CustomHttpClient implements HttpClient
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // your logic of processing request
    }
}

class ExtendedHttpUserProviderFactory extends HttpUserProviderFactory
{
    protected static function getClient(HttpClientType $type): HttpClient
    {
        if ($type->equals(ExtendedHttpClientType::Custom())) {
            return new CustomHttpClient();
        }

        return parent::getClient($type);
    }
}

$userProvider = ExtendedHttpUserProviderFactory::createProvider(
    ExtendedHttpClientType::Custom(),
    CompositeUserProcessorFactory::createProcessor()
);
````
#### GiveawayProvider implementations
+ [HttpGiveawayProvider](#httpgiveawayprovider)

##### HttpGiveawayProvider
`HttpGiveawayProvider` implements `GiveawayProvider` via [HTTPlug](https://github.com/php-http/httplug).

using via **[Curl](https://github.com/php-http/curl-client)**
```
composer require php-http/curl-client
```

```php
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\UseCase\GetUser;

$giveawayProvider = HttpGiveawayProviderFactory::createProvider(
    HttpClientType::Curl(), 
    CompositeGiveawayProcessorFactory::createProcessor(new GetUser\Interactor($userProvider))
);
````
using via **[Guzzle](https://github.com/php-http/guzzle6-adapter)**
```
composer require php-http/guzzle6-adapter
```

```php
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\UseCase\GetUser;

$giveawayProvider = HttpGiveawayProviderFactory::createProvider(
    HttpClientType::Guzzle(), 
    CompositeGiveawayProcessorFactory::createProcessor(new GetUser\Interactor($userProvider))
);
````
using via **[Buzz](https://github.com/kriswallsmith/Buzz)**
```
composer require kriswallsmith/buzz
```

```php
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\UseCase\GetUser;

$giveawayProvider = HttpGiveawayProviderFactory::createProvider(
    HttpClientType::Buzz(), 
    CompositeGiveawayProcessorFactory::createProcessor(new GetUser\Interactor($userProvider))
);
````
using via your implementation
```php
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Marmozist\SteamGifts\UseCase\GetUser;

/**
 * @method static self Custom()
 */
class ExtendedHttpClientType extends HttpClientType
{
    const Custom = 'Custom';
}

class CustomHttpClient implements HttpClient
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // your logic of processing request
    }
}

class ExtendedHttpGiveawayProviderFactory extends HttpGiveawayProviderFactory
{
    protected static function getClient(HttpClientType $type): HttpClient
    {
        if ($type->equals(ExtendedHttpClientType::Custom())) {
            return new CustomHttpClient();
        }

        return parent::getClient($type);
    }
}

$giveawayProvider = ExtendedHttpGiveawayProviderFactory::createProvider(
    ExtendedHttpClientType::Custom(),
    CompositeGiveawayProcessorFactory::createProcessor(new GetUser\Interactor($userProvider))
);
````

#### Client methods
+ [GetUser](#getuser)
+ [GetUserList](#getuserlist)
+ [GetGiveaway](#getgiveaway)
+ [GetGiveawayList](#getgiveawaylist)

##### GetUser
e.g. https://www.steamgifts.com/user/Gotman
```php
$user = $client->getUser('Gotman');

if (!$user) {
    throw new \Exception('User not found');
}

echo 'Name: '.$user->getName().PHP_EOL;
echo 'Role: '.$user->getRole()->getValue().PHP_EOL;
echo 'Last Online: '.$user->getLastOnlineAt()->format('Y-m-d H:i:s').PHP_EOL;
echo 'Registered: '.$user->getRegisteredAt()->format('Y-m-d H:i:s').PHP_EOL;
echo 'Avatar: '.$user->getAvatarUrl().PHP_EOL;
echo 'Steam: '.$user->getSteamLink().PHP_EOL;
echo 'Comments: '.$user->getComments().PHP_EOL;
echo 'Entered: '.$user->getEnteredGiveaways().PHP_EOL;
echo 'Gifts Won: '.$user->getGiftsWon().PHP_EOL;
echo 'Gifts Sent: '.$user->getGiftsSent().PHP_EOL;
echo 'Contributor Level: '.$user->getContributorLevel().PHP_EOL;
```

##### GetUserList
```php
$userList = $client->getUserList(['Gotman', 'Batman']);
$user = $userList->findUser('Gotman');

echo 'Entered Giveaways: '.$user->getEnteredGiveaways().PHP_EOL;
```

##### GetGiveaway
e.g. https://www.steamgifts.com/giveaway/O8NIm/
```php
$giveaway = $client->getGiveaway('O8NIm');

if (!$giveaway) {
    throw new \Exception('Giveaway not found');
}

echo 'Id: '.$giveaway->getId().PHP_EOL;
echo 'Name: '.$giveaway->getName().PHP_EOL;
echo 'Creator: '.$giveaway->getCreator()->getName().PHP_EOL;
echo 'Created at: '.$giveaway->getCreatedAt()->format('Y-m-d H:i:s').PHP_EOL;
echo 'Finished at: '.$giveaway->getFinishedAt()->format('Y-m-d H:i:s').PHP_EOL;
echo 'Steam: '.$giveaway->getSteamLink().PHP_EOL;
echo 'Cost: '.$giveaway->getCost().PHP_EOL;
echo 'Copies: '.$giveaway->getCopies().PHP_EOL;
echo 'Level: '.$giveaway->getLevel().PHP_EOL;
echo 'Entries: '.$giveaway->getEntries().PHP_EOL;
echo 'Comments: '.$giveaway->getComments().PHP_EOL;
echo 'Region restricted: '.(int)$giveaway->isRegionRestricted().PHP_EOL;
echo 'Group: '.(int)$giveaway->isGroup().PHP_EOL;
echo 'Invite only: '.(int)$giveaway->isInviteOnly().PHP_EOL;
echo 'Whitelist: '.(int)$giveaway->isWhitelist().PHP_EOL;
```

##### GetGiveawayList
```php
$giveawayList = $client->getGiveawayList(['O8NIm', '1BWVk']);
$giveaway = $giveawayList->findGiveaway('O8NIm');

echo 'Name: '.$giveaway->getName().PHP_EOL;
```
