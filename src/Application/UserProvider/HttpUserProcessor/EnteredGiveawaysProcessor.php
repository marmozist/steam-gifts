<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class EnteredGiveawaysProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='featured__table__column'][2]/div[@class='featured__table__row'][1]/div[@class='featured__table__row__right'][1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $enteredGiveaways = $this->prepareEnteredGiveaways($this->getNodeText($expression));
            $builder->setEnteredGiveaways($enteredGiveaways);
        }
    }

    protected function prepareEnteredGiveaways(string $enteredGiveawaysRaw): int
    {
        return (int)strtr($enteredGiveawaysRaw, [',' => '']);
    }
}
