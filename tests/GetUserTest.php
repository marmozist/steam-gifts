<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts;


use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use PHPUnit\Framework\TestCase;
use Buzz\Client as Buzz;
use Http\Adapter\Guzzle6;
use Http\Client\Curl;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 *
 * @coversNothing
 */
class GetUserTest extends TestCase
{
    /**
     * @param HttpClient $client
     * @dataProvider httpClientExamples
     */
    public function testGetUser(HttpClient $client): void
    {
        $httpProvider = $this->createHttpUserProvider($client);
        $client = ClientFactory::createClient($httpProvider);

        /** @var User $user */
        $user = $client->getUser('Gotman');
        expect($user)->isInstanceOf(User::class);
        expect($user->getName())->same('Gotman');
        expect($user->getRole())->equals(UserRole::Member());
        expect($user->getLastOnlineAt())->equals(new \DateTimeImmutable('2020-03-21T09:27:50.000000+0000'));
        expect($user->getRegisteredAt())->equals(new \DateTimeImmutable('2017-01-16T15:27:13.000000+0000'));
        expect($user->getAvatarUrl())->same('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/0c/0c6d0b40c4dfeb935c67242eb50b2148f933ebde_full.jpg');
        expect($user->getSteamLink())->same('https://steamcommunity.com/profiles/76561198116076000');
        expect($user->getComments())->same(37);
        expect($user->getEnteredGiveaways())->same(155876);
        expect($user->getGiftsWon())->same(714);
        expect($user->getGiftsSent())->same(51);
        expect($user->getContributorLevel())->same(4.37);
    }

    /**
     * @test
     * @param HttpClient $client
     * @dataProvider httpClientExamples
     */
    public function returnsNullWhenUserNotFound(HttpClient $client): void
    {
        $httpProvider = $this->createHttpUserProvider($client);
        $client = ClientFactory::createClient($httpProvider);
        $user = $client->getUser('Undefined123');
        expect($user)->null();
    }

    /**
     * @return array[]
     */
    public function httpClientExamples(): array
    {
        return [
            [new Guzzle6\Client()],
            [new Curl\Client()],
            [new Buzz\FileGetContents(new DiactorosMessageFactory())],
        ];
    }

    private function createPluginClient(HttpClient $client): PluginClient
    {
        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__ . '/Fixtures/http');

        $record = new RecordPlugin($namingStrategy, $recorder);
        $replay = new ReplayPlugin($namingStrategy, $recorder);

        return new PluginClient(
            $client,
            [
                //$record,
                $replay
            ]
        );
    }

    private function createHttpUserProvider(HttpClient $client): HttpUserProvider
    {
        return new HttpUserProvider($this->createPluginClient($client), new DiactorosMessageFactory(), CompositeUserProcessorFactory::createProcessor());
    }
}
