<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application;


use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Application\ClientFactory;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList;
use Marmozist\SteamGifts\UseCase\GetUser\UserProvider;
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
        $userProvider = $this->prophesize(UserProvider::class)->reveal();
        $getUserInteractor = new GetUser\Interactor($userProvider);
        $getUserListInteractor = new GetUserList\Interactor($getUserInteractor);
        $expectedClient = new Client($getUserInteractor, $getUserListInteractor);
        $client = ClientFactory::createClient($userProvider);

        expect($expectedClient)->equals($client);
    }
}
