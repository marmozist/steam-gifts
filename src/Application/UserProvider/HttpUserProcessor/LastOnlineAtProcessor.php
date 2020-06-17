<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LastOnlineAtProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='featured__table__column'][1]/div[@class='featured__table__row'][2]/div[@class='featured__table__row__right'][1]/span[1]/@data-timestamp";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $timestamp = (int)$this->getNodeText($expression);
            $dateTime = (new \DateTimeImmutable())->setTimestamp($timestamp);
            $builder->setLastOnlineAt($dateTime);
        } elseif($this->hasNode("//span[@class='featured__online-now'][1]/text()[1]")) {
            $dateTime = (new \DateTimeImmutable())->setTimestamp(2147483647);
            $builder->setLastOnlineAt($dateTime);
        }
    }
}
