<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts;


use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProvider;
use Marmozist\SteamGifts\Application\UserProvider\InMemoryUserProvider;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;
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
class GetGiveawayTest extends TestCase
{
    /**
     * @param Client $client
     * @dataProvider httpClientExamples
     */
    public function testGetGiveaway(Client $client): void
    {
        /** @var Giveaway $giveaway */
        $giveaway = $client->getGiveaway('9KfZs');
        expect($giveaway)->isInstanceOf(Giveaway::class);
        expect($giveaway->getId())->same('9KfZs');
        expect($giveaway->getName())->same('Dead Inside');
        expect($giveaway->getSteamLink())->same('https://store.steampowered.com/app/554900/');
        expect($giveaway->getLevel())->same(4);
        expect($giveaway->getFinishedAt())->equals(new \DateTimeImmutable('2017-06-27T20:00:00.000000+0000'));
        expect($giveaway->getCreatedAt())->equals(new \DateTimeImmutable('2017-06-26T19:13:12.000000+0000'));
        expect($giveaway->getEntries())->same(911);
        expect($giveaway->getCreator())->equals($client->getUser('Gotman'));
        expect($giveaway->getCost())->same(2);
        expect($giveaway->getCopies())->same(10);
        expect($giveaway->getComments())->same(10);
    }

    /**
     * @test
     * @param Client $client
     * @dataProvider httpClientExamples
     */
    public function returnsNullWhenGiveawayNotFound(Client $client): void
    {
        $giveaway = $client->getGiveaway('9KfKs');
        expect($giveaway)->null();
    }

    /**
     * @return array[]
     */
    public function httpClientExamples(): array
    {
        return [
            [$this->createHttpClient(new Guzzle6\Client())],
            [$this->createHttpClient(new Curl\Client())],
            [$this->createHttpClient(new Buzz\FileGetContents(new DiactorosMessageFactory()))],
        ];
    }

    private function createHttpClient(HttpClient $client): Client
    {
        $userProvider = new InMemoryUserProvider();

        return ClientFactory::createClient($userProvider, new HttpGiveawayProvider(
            $this->createPluginClient($client),
            new DiactorosMessageFactory(),
            CompositeGiveawayProcessorFactory::createProcessor(new Interactor($userProvider)),
        ));
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
}
