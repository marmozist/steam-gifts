<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetUser;


use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
interface UserProvider
{
    /**
     * @param string $username
     * @return User
     * @throws UserNotFound
     */
    public function getUser(string $username): User;
}
