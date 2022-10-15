<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetUserList;


use Generator;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class Interactor
{
    private GetUser\Interactor $getUserInteractor;

    public function __construct(GetUser\Interactor $getUserInteractor)
    {
        $this->getUserInteractor = $getUserInteractor;
    }

    /**
     * @param string[] $usernames
     * @return UserList
     */
    public function getUserList(array $usernames): UserList
    {
        $generator = $this->createGenerator($usernames);

        return new UserList($generator);
    }

    /**
     * @param string[] $usernames
     * @return Generator<User>
     */
    private function createGenerator(array $usernames): Generator
    {
        foreach ($usernames as $username) {
            $user = $this->getUserInteractor->getUser($username);
            if ($user === null) {
                continue;
            }

            yield $user;
        }
    }
}
