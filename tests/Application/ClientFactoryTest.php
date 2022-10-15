<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application;


use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use Marmozist\SteamGifts\UseCase\GetGiveawayList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ClientFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateClient(): void
    {
        $userProvider = $this->prophesize(GetUser\UserProvider::class)->reveal();
        $giveawayProvider = $this->prophesize(GetGiveaway\GiveawayProvider::class)->reveal();
        $getUserInteractor = new GetUser\Interactor($userProvider);
        $getUserListInteractor = new GetUserList\Interactor($getUserInteractor);
        $getGiveawayInteractor = new GetGiveaway\Interactor($giveawayProvider);
        $getGiveawayListInteractor = new GetGiveawayList\Interactor($getGiveawayInteractor);
        $expectedClient = new Client($getUserInteractor, $getUserListInteractor, $getGiveawayInteractor, $getGiveawayListInteractor);
        $client = ClientFactory::createClient($userProvider, $giveawayProvider);

        expect($expectedClient)->toEqual($client);
    }
}
