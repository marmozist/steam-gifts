<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application;


use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use Marmozist\SteamGifts\UseCase\GetUserList;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ClientFactory
{
    public static function createClient(GetUser\UserProvider $userProvider, GetGiveaway\GiveawayProvider $giveawayProvider): Client
    {
        $getUserInteractor = new GetUser\Interactor($userProvider);
        $getUserListInteractor = new GetUserList\Interactor($getUserInteractor);
        $getGiveawayInteractor = new GetGiveaway\Interactor($giveawayProvider);

        return new Client($getUserInteractor, $getUserListInteractor, $getGiveawayInteractor);
    }
}
