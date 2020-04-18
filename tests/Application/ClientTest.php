<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application;


use Marmozist\SteamGifts\Application\Client;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
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
    private ObjectProphecy $getUserListInteractor;
    private ObjectProphecy $getGiveawayInteractor;
    private Client $client;

    protected function setUp(): void
    {
        $this->getUserInteractor = $this->prophesize(GetUser\Interactor::class);
        $this->getUserListInteractor = $this->prophesize(GetUserList\Interactor::class);
        $this->getGiveawayInteractor = $this->prophesize(GetGiveaway\Interactor::class);
        $this->client = new Client(
            $this->getUserInteractor->reveal(),
            $this->getUserListInteractor->reveal(),
            $this->getGiveawayInteractor->reveal()
        );
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

    public function testGetUserList(): void
    {
        $username1 = 'Gotman';
        $username2 = 'Batman';
        $usernames = [$username1, $username2];
        $user = User::createBuilder()->setName($username1)->build();
        $this->getUserListInteractor->getUserList($usernames)->shouldBeCalled()->willReturn(new GetUserList\UserList(new \ArrayIterator([$user])));
        $result = $this->client->getUserList($usernames);

        expect($result)->isInstanceOf(GetUserList\UserList::class);
        expect($result)->count(1);
        expect($result->findUser($username1))->same($user);
        expect($result->findUser($username2))->null();
    }

    public function testGetGiveaway(): void
    {
        $giveawayId = 'O8NIm';
        $giveaway = Giveaway::createBuilder()->setName($giveawayId)->build();
        $this->getGiveawayInteractor->getGiveaway($giveawayId)->shouldBeCalled()->willReturn($giveaway);
        $result = $this->client->getGiveaway($giveawayId);

        expect($result)->isInstanceOf(Giveaway::class);
        expect($result)->same($giveaway);
    }
}
