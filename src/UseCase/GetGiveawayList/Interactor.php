<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetGiveawayList;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use Generator;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class Interactor
{
    private GetGiveaway\Interactor $getGiveawayInteractor;

    public function __construct(GetGiveaway\Interactor $getGiveawayInteractor)
    {
        $this->getGiveawayInteractor = $getGiveawayInteractor;
    }

    /**
     * @param string[] $giveawayIds
     * @return GiveawayList
     */
    public function getGiveawayList(array $giveawayIds): GiveawayList
    {
        $generator = $this->createGenerator($giveawayIds);

        return new GiveawayList($generator);
    }

    /**
     * @param string[] $giveawayIds
     * @return Generator<Giveaway>
     */
    private function createGenerator(array $giveawayIds): Generator
    {
        foreach ($giveawayIds as $giveawayId) {
            $giveaway = $giveaway = $this->getGiveawayInteractor->getGiveaway($giveawayId);
            if ($giveaway === null) {
                continue;
            }

            yield $giveaway;
        }
    }
}
