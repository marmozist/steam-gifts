<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetUserList;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUserList\UserList;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class UserListTest extends TestCase
{
    public function testUserList(): void
    {
        $user1 = User::createBuilder('User1')->build();
        $user2 = User::createBuilder('user2')->build();

        $generator = static function () use ($user1, $user2): \Generator {
            foreach ([$user1, $user2] as $user) {
                yield $user;
            }
        };

        $iterator = $generator();
        $list = new UserList($iterator);
        expect($list)->count(2);
        expect($list->findUser('USER1'))->same($user1);
        expect($list->findUser('User2'))->same($user2);
        expect($list->findUser('user3'))->null();
        expect($list->getIterator())->same($iterator);
    }

    public function testEmptyUserList(): void
    {
        $list = new UserList(new \ArrayIterator([]));
        expect($list)->count(0);
        expect($list->findUser('USER1'))->null();
        expect($list->findUser('User2'))->null();
        expect($list->findUser('user3'))->null();
    }
}
