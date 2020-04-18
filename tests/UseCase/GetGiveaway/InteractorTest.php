<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetGiveaway;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayNotFound;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayProvider;
use Marmozist\SteamGifts\UseCase\GetGiveaway\Interactor;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InteractorTest extends TestCase
{
    private Interactor $interactor;
    private ObjectProphecy $provider;

    protected function setUp(): void
    {
        $this->provider = $this->prophesize(GiveawayProvider::class);
        $this->interactor = new Interactor($this->provider->reveal());
    }

    public function testGetGiveaway(): void
    {
        $giveawayId = 'O8NIm';
        $giveaway = $this->prophesize(Giveaway::class)->reveal();
        $this->provider->getGiveaway($giveawayId)->shouldBeCalled()->willReturn($giveaway);

        expect($this->interactor->getGiveaway($giveawayId))->same($giveaway);
    }

    /**
     * @test
     */
    public function returnsNullWhenThrowsGiveawayProvider(): void
    {
        $giveawayId = 'O8NIm';
        $this->provider->getGiveaway($giveawayId)->shouldBeCalled()->willThrow(GiveawayNotFound::class);

        expect($this->interactor->getGiveaway($giveawayId))->null();
    }
}
