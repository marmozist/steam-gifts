<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetUser;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;
use Marmozist\SteamGifts\UseCase\GetUser\UserNotFound;
use Marmozist\SteamGifts\UseCase\GetUser\UserProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InteractorTest extends TestCase
{
    private Interactor $interactor;
    private ObjectProphecy $provider;

    protected function setUp(): void
    {
        $this->provider = $this->prophesize(UserProvider::class);
        $this->interactor = new Interactor($this->provider->reveal());
    }

    public function testGetUser(): void
    {
        $username = 'Gotman';
        $user = $this->prophesize(User::class)->reveal();
        $this->provider->getUser($username)->shouldBeCalled()->willReturn($user);

        expect($this->interactor->getUser($username))->same($user);
    }

    /**
     * @test
     */
    public function returnsNullWhenThrowsUserProvider(): void
    {
        $username = 'Gotman';
        $this->provider->getUser($username)->shouldBeCalled()->willThrow(UserNotFound::class);

        expect($this->interactor->getUser($username))->null();
    }
}
