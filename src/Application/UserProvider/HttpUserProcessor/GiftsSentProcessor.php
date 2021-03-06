<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiftsSentProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class=' featured__table__row__right'][1]/span[1]/span[1]/a[1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $giftsSent = $this->prepareGiftsSent($this->getNodeText($expression));
            $builder->setGiftsSent($giftsSent);
        }
    }

    protected function prepareGiftsSent(string $giftsSentRaw): int
    {
        return (int)strtr($giftsSentRaw, [',' => '']);
    }
}
