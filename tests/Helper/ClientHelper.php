<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Helper;


use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient as BaseHttpClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProvider;
use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Application\HttpClient\HttpClientParameters;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ClientHelper
{
    public static function createRecordClient(BaseHttpClient $client): Client
    {
        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__ . '/Fixtures/http');
        $record = new RecordPlugin($namingStrategy, $recorder);

        return self::createClient($client, $record);
    }

    public static function createReplayClient(BaseHttpClient $client): Client
    {
        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__ . '/../Fixtures/http');
        $replay = new ReplayPlugin($namingStrategy, $recorder);

        return self::createClient($client, $replay);
    }

    private static function createClient(BaseHttpClient $client, Plugin $plugin): Client
    {
        $httpClient = new HttpClient(
            new PluginClient($client, [$plugin]),
            new DiactorosMessageFactory(), HttpClientParameters::createBuilder()->build()
        );

        $userProvider = new HttpUserProvider(
            $httpClient,
            CompositeUserProcessorFactory::createProcessor(),
        );

        $giveawayProvider = new HttpGiveawayProvider(
            $httpClient,
            CompositeGiveawayProcessorFactory::createProcessor(new Interactor($userProvider))
        );

        return ClientFactory::createClient($userProvider, $giveawayProvider);
    }
}
