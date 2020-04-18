<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetGiveaway;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
interface GiveawayProvider
{
    /**
     * @param string $giveawayId
     * @return Giveaway
     * @throws GiveawayNotFound
     */
    public function getGiveaway(string $giveawayId): Giveaway;
}
