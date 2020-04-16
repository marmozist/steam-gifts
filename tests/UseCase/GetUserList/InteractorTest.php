<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetUserList;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUserList\Interactor;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList\UserList;
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
    private ObjectProphecy $getUserInteractor;

    protected function setUp(): void
    {
        $this->getUserInteractor = $this->prophesize(GetUser\Interactor::class);
        $this->interactor = new Interactor($this->getUserInteractor->reveal());
    }

    public function testGetUserList(): void
    {
        $username1 = 'Gotman';
        $username2 = 'Batman';
        $user = User::createBuilder($username1)->build();
        $this->getUserInteractor->getUser($username1)->shouldBeCalled()->willReturn($user);
        $this->getUserInteractor->getUser($username2)->shouldBeCalled()->willReturn(null);

        $result = $this->interactor->getUserList([$username1, $username2]);
        expect($result)->isInstanceOf(UserList::class);
        expect($result)->count(1);
        expect($result->findUser($username2))->null();
        expect($result->findUser($username1))->same($user);
    }
}
