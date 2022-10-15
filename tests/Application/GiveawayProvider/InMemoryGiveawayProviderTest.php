<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider;


use Marmozist\SteamGifts\Application\GiveawayProvider\InMemoryGiveawayProvider;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InMemoryGiveawayProviderTest extends TestCase
{
    private InMemoryGiveawayProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new InMemoryGiveawayProvider();
    }

    public function testGetGiveaway(): void
    {
        $giveawayId = 'O8NIm';
        expect($this->provider->getGiveaway($giveawayId)->getName())->toBe('Need For Speed: Hot Pursuit');
    }
}
