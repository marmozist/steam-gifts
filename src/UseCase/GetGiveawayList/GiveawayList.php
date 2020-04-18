<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\UseCase\GetGiveawayList;


use IteratorAggregate;
use Iterator;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Traversable;
use Countable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiveawayList implements IteratorAggregate, Countable
{
    /**
     * @var Iterator<Giveaway>
     */
    private Iterator $giveawayList;

    /**
     * @var Giveaway[]
     */
    private array $giveaways;

    /**
     * @param Iterator<Giveaway> $giveawayList
     */
    public function __construct(Iterator $giveawayList)
    {
        $this->giveawayList = $giveawayList;
        $this->giveaways = [];
    }

    /**
     * @return Traversable<Giveaway>
     */
    public function getIterator(): Traversable
    {
        return $this->giveawayList;
    }

    public function findGiveaway(string $giveawayId): ?Giveaway
    {
        $this->revealIterator();

        return $this->giveaways[strtolower($giveawayId)] ?? null;
    }

    public function count(): int
    {
        $this->revealIterator();

        return count($this->giveaways);
    }

    private function revealIterator(): void
    {
        if (!$this->giveawayList->valid()) {
            return;
        }

        foreach ($this->giveawayList as $giveaway) {
            $this->giveaways[strtolower($giveaway->getId())] = $giveaway;
        }
    }
}
