<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser;
use Marmozist\SteamGifts\UseCase\GetUserList;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use Marmozist\SteamGifts\UseCase\GetGiveawayList;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class Client
{
    private GetUser\Interactor $getUserInteractor;
    private GetUserList\Interactor $getUserListInteractor;
    private GetGiveaway\Interactor $getGiveawayInteractor;
    private GetGiveawayList\Interactor $getGiveawayListInteractor;

    public function __construct(
        GetUser\Interactor $getUserInteractor,
        GetUserList\Interactor $getUserListInteractor,
        GetGiveaway\Interactor $getGiveawayInteractor,
        GetGiveawayList\Interactor $getGiveawayListInteractor
    ) {
        $this->getUserInteractor = $getUserInteractor;
        $this->getUserListInteractor = $getUserListInteractor;
        $this->getGiveawayInteractor = $getGiveawayInteractor;
        $this->getGiveawayListInteractor = $getGiveawayListInteractor;
    }

    public function getUser(string $username): ?User
    {
        return $this->getUserInteractor->getUser($username);
    }

    /**
     * @param string[] $usernames
     * @return GetUserList\UserList
     */
    public function getUserList(array $usernames): GetUserList\UserList
    {
        return $this->getUserListInteractor->getUserList($usernames);
    }

    public function getGiveaway(string $giveawayId): ?Giveaway
    {
        return $this->getGiveawayInteractor->getGiveaway($giveawayId);
    }

    /**
     * @param string[] $giveawayIds
     * @return GetGiveawayList\GiveawayList
     */
    public function getGiveawayList(array $giveawayIds): GetGiveawayList\GiveawayList
    {
        return $this->getGiveawayListInteractor->getGiveawayList($giveawayIds);
    }
}
