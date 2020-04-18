<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application;


use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ClientFactoryTest extends TestCase
{
    public function testCreateClient(): void
    {
        $userProvider = $this->prophesize(GetUser\UserProvider::class)->reveal();
        $giveawayProvider = $this->prophesize(GetGiveaway\GiveawayProvider::class)->reveal();
        $getUserInteractor = new GetUser\Interactor($userProvider);
        $getUserListInteractor = new GetUserList\Interactor($getUserInteractor);
        $getGiveawayInteractor = new GetGiveaway\Interactor($giveawayProvider);
        $expectedClient = new Client($getUserInteractor, $getUserListInteractor, $getGiveawayInteractor);
        $client = ClientFactory::createClient($userProvider, $giveawayProvider);

        expect($expectedClient)->equals($client);
    }
}
