<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CostProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $this->load($content);
        $expression = $this->prepareExpression("//div[@class='featured__heading__small'][2]/text()[1]");

        if ($this->hasNode($expression)) {
            $cost = $this->prepareCost($this->getNodeText($expression));
            $builder->setCost($cost);
        }
    }

    protected function prepareCost(string $costRaw): int
    {
        return (int)strtr($costRaw, ['(' => '', 'P)' => '']);
    }

    protected function prepareExpression(string $expression): string
    {
        return $this->hasNode($expression) ? $expression : '//div[@class=\'featured__heading__small\'][1]/text()[1]';
    }
}
