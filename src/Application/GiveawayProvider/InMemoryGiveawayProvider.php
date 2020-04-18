<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayProvider;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InMemoryGiveawayProvider implements GiveawayProvider
{
    public function getGiveaway(string $giveawayId): Giveaway
    {
        $user = User::createBuilder('Gotman')->build();

        $builder = Giveaway::createBuilder($giveawayId);
        $builder
            ->setName('Need For Speed: Hot Pursuit')
            ->setCreator($user)
            ->setCreatedAt(new \DateTimeImmutable('2017-02-13 18:28'))
            ->setFinishedAd(new \DateTimeImmutable('2017-02-14 08:30'))
            ->setSteamLink('https://store.steampowered.com/app/47870/Need_For_Speed_Hot_Pursuit/')
            ->setCost(20)
            ->setCopies(1)
            ->setLevel(3)
            ->setEntries(212)
            ->setComments(2)
            ->setRegionRestricted(true)
            ->setGroup(false)
            ->setInviteOnly(false)
            ->setWhitelist(false);

        return $builder->build();
    }
}
