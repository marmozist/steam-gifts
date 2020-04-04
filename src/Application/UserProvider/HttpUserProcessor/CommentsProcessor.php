<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CommentsProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='featured__table__column'][1]/div[@class='featured__table__row'][4]/div[@class='featured__table__row__right'][1]/text()[1]";
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
