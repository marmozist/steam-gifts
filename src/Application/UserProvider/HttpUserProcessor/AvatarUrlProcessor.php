<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class AvatarUrlProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='global__image-inner-wrap'][1]/@style";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $styleAttr = $this->getNodeText($expression);
            preg_match('/background-image:url\((.*?)\);/s', $styleAttr, $matches);
            $builder->setAvatarUrl($matches[1] ?? '');
        }
    }
}
