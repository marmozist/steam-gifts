<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CreatedAtProcessor implements GiveawayProcessor
{
    use XPathTrait;

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//div[@class='featured__column featured__column--width-fill text-right'][1]/span[1]/@data-timestamp";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $timestamp = (int)$this->getNodeText($expression);
            $dateTime = (new \DateTimeImmutable())->setTimestamp($timestamp);
            $builder->setCreatedAt($dateTime);
        }
    }
}
