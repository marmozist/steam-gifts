<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CommentsProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//li[@class='sidebar__navigation__item is-selected'][1]/a[@class='sidebar__navigation__item__link'][1]/div[@class='sidebar__navigation__item__count'][1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $comments = $this->prepareComments($this->getNodeText($expression));
            $builder->setComments($comments);
        }
    }

    protected function prepareComments(string $commentsRaw): int
    {
        return (int)strtr($commentsRaw, [',' => '']);
    }
}
