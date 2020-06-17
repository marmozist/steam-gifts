<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\Proxy\LazyUserProxy;
use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;
use Marmozist\SteamGifts\UseCase\GetUser;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CreatorProcessor implements GiveawayProcessor
{
    use XPathTrait;

    private GetUser\Interactor $interactor;

    public function __construct(GetUser\Interactor $interactor)
    {
        $this->interactor = $interactor;
    }

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        $expression = "//div[@class='featured__column featured__column--width-fill text-right'][1]/a[1]/text()[1]";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $username = $this->getNodeText($expression);
            $user = new LazyUserProxy($this->interactor, $username);
            $builder->setCreator($user);
        }
    }
}
