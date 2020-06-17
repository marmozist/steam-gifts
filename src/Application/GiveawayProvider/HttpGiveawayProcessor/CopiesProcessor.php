<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;

use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CopiesProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//div[@class='featured__heading__small'][1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $copies = $this->prepareCopies($this->getNodeText($expression));
            $builder->setCopies($copies);
        }
    }

    protected function prepareCopies(string $copiesRaw): int
    {
        if (strpos($copiesRaw, 'Copies') === false) {
            return 1;
        }

        return (int)strtr($copiesRaw, ['(' => '', ' Copies)' => '']);
    }
}
