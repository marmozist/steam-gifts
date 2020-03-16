<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\SteamGifts\UseCase\GetUser\UserProvider;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InMemoryUserProvider implements UserProvider
{
    public function getUser(string $username): User
    {
        $builder = User::createBuilder($username);
        $builder
            ->setRole(UserRole::Member())
            ->setLastOnlineAt(new \DateTimeImmutable('-3 days'))
            ->setRegisteredAt(new \DateTimeImmutable('2017-01-16 17:27'))
            ->setAvatarUrl('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/0c/0c6d0b40c4dfeb935c67242eb50b2148f933ebde_full.jpg')
            ->setSteamLink('https://steamcommunity.com/profiles/76561198116076000')
            ->setComments(37)
            ->setEnteredGiveaways(155876)
            ->setGiftsWon(714)
            ->setGiftsSent(51)
            ->setContributorLevel(4.37);

        return $builder->build();
    }
}
