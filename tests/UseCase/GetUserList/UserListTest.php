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
        expect($list)->arrayToHaveCount(2);
        expect($list->findUser('USER1'))->toBe($user1);
        expect($list->findUser('User2'))->toBe($user2);
        expect($list->findUser('user3'))->toBeNull();
        expect($list->getIterator())->toBe($iterator);
    }

    public function testEmptyUserList(): void
    {
        $list = new UserList(new \ArrayIterator([]));
        expect($list)->arrayToHaveCount(0);
        expect($list->findUser('USER1'))->toBeNull();
        expect($list->findUser('User2'))->toBeNull();
        expect($list->findUser('user3'))->toBeNull();
    }
}
