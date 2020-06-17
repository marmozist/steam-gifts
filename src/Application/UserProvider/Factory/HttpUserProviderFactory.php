<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\Factory;


use Buzz\Client as Buzz;
use Http\Adapter\Guzzle6;
use Http\Client\Curl;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpUserProviderFactory
{
    public static function createProvider(HttpClientType $type, UserProcessor $userProcessor): HttpUserProvider
    {
        return new HttpUserProvider(static::getClient($type), static::getFactory($type), $userProcessor);
    }

    protected static function getClient(HttpClientType $type): HttpClient
    {
        switch ($type) {
            case HttpClientType::Guzzle():
                return new Guzzle6\Client();
            case HttpClientType::Curl():
                return new Curl\Client();
            case HttpClientType::Buzz():
                return new Buzz\FileGetContents(static::getFactory($type));
            default:
                throw new \InvalidArgumentException("Unsupported http client type '$type'");
        }
    }

    protected static function getFactory(HttpClientType $type): MessageFactory
    {
        switch ($type) {
            case HttpClientType::Guzzle():
                return new GuzzleMessageFactory();
            case HttpClientType::Curl():
            case HttpClientType::Buzz():
            default:
                return new DiactorosMessageFactory();
        }
    }
}
