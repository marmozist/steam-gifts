<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application;


use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ClientTest extends TestCase
{
    private ObjectProphecy $getUserInteractor;
    private Client $client;

    protected function setUp(): void
    {
        $this->getUserInteractor = $this->prophesize(GetUser\Interactor::class);
        $this->client = new Client($this->getUserInteractor->reveal());
    }

    public function testGetUser(): void
    {
        $username = 'Gotman';
        $user = User::createBuilder()->setName($username)->build();
        $this->getUserInteractor->getUser($username)->shouldBeCalled()->willReturn($user);
        $result = $this->client->getUser($username);

        expect($result)->isInstanceOf(User::class);
        expect($result)->same($user);
    }
}
