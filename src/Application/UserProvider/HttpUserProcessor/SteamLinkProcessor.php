<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class SteamLinkProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='sidebar__shortcut-inner-wrap'][1]/a[1]/@href";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $builder->setSteamLink($this->getNodeText($expression));
        }
    }
}
