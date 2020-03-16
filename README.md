PHP Client for [SteamGifts](https://www.steamgifts.com/)
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

```
composer require marmozist/steam-gifts
```

## Usage

#### Use factory to create client
```php
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\Application\UserProvider\InMemoryUserProvider;

$client = ClientFactory::createClient(new InMemoryUserProvider());
```
You need to implement `UserProvider` interface and pass instance to `createClient` method.

#### Available client methods
+ [GetUser](#getuser)

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
