<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LevelProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//div[@class='featured__column featured__column--contributor-level featured__column--contributor-level--negative'][1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $level = $this->prepareLevel($this->getNodeText($expression));
            $builder->setLevel($level);
        }
    }

    protected function prepareLevel(string $levelRaw): int
    {
        return (int)strtr($levelRaw, ['Level ' => '', '+' => '']);
    }
}
