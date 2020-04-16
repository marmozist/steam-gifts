<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetUserList;


use IteratorAggregate;
use Iterator;
use Traversable;
use Countable;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class UserList implements IteratorAggregate, Countable
{
    /**
     * @var Iterator<User>
     */
    private Iterator $userList;

    /**
     * @var User[]
     */
    private array $users;

    /**
     * @param Iterator<User> $userList
     */
    public function __construct(Iterator $userList)
    {
        $this->userList = $userList;
        $this->users = [];
    }

    /**
     * @return Traversable<User>
     */
    public function getIterator(): Traversable
    {
        return $this->userList;
    }

    public function findUser(string $username): ?User
    {
        $this->revealIterator();

        return $this->users[strtolower($username)] ?? null;
    }
    
    public function count(): int
    {
        $this->revealIterator();

        return count($this->users);
    }

    private function revealIterator(): void
    {
        if (!$this->userList->valid()) {
            return;
        }

        foreach ($this->userList as $user) {
            $this->users[strtolower($user->getName())] = $user;
        }
    }
}
