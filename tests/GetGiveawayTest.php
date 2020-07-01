<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts;


use Http\Client\HttpClient;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Marmozist\SteamGifts\Application\Proxy\LazyUserProxy;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\Tests\SteamGifts\Helper\ClientHelper;
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
     * @param HttpClient $httpClient
     * @dataProvider httpClientExamples
     */
    public function testGetGiveaway(HttpClient $httpClient): void
    {
        $client = ClientHelper::createReplayClient($httpClient);
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
        expect($giveaway->getCreator())->isInstanceOf(LazyUserProxy::class);
        expect($giveaway->getCreator()->getName())->same('Gotman');
        expect($giveaway->getCreator()->getRole())->equals(UserRole::Member());
        expect($giveaway->getCost())->same(2);
        expect($giveaway->getCopies())->same(10);
        expect($giveaway->getComments())->same(10);
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
}
