<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class Client
{
    private GetUser\Interactor $getUserInteractor;

    public function __construct(GetUser\Interactor $getUserInteractor)
    {
        $this->getUserInteractor = $getUserInteractor;
    }

    public function getUser(string $username): ?User
    {
        return $this->getUserInteractor->getUser($username);
    }
}
