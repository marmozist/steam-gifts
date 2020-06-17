<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;

use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class EntriesProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//div[@class='sidebar__navigation__item__count live__entry-count'][1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $entries = $this->prepareEntries($this->getNodeText($expression));
            $builder->setEntries($entries);
        }
    }

    protected function prepareEntries(string $enteredGiveawaysRaw): int
    {
        return (int)strtr($enteredGiveawaysRaw, [',' => '']);
    }
}
