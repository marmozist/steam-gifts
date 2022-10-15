<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetGiveawayList;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveawayList\GiveawayList;
use Marmozist\SteamGifts\UseCase\GetGiveawayList\Interactor;
use Marmozist\SteamGifts\UseCase\GetGiveaway;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InteractorTest extends TestCase
{
    use ProphecyTrait;

    private Interactor $interactor;
    private ObjectProphecy $getGiveawayInteractor;

    protected function setUp(): void
    {
        $this->getGiveawayInteractor = $this->prophesize(GetGiveaway\Interactor::class);
        $this->interactor = new Interactor($this->getGiveawayInteractor->reveal());
    }

    public function testGetGiveawayList(): void
    {
        $giveawayId1 = 'O8NIm';
        $giveawayId2 = 'S4sRN';
        $giveaway = Giveaway::createBuilder($giveawayId1)->build();
        $this->getGiveawayInteractor->getGiveaway($giveawayId1)->shouldBeCalled()->willReturn($giveaway);
        $this->getGiveawayInteractor->getGiveaway($giveawayId2)->shouldBeCalled()->willReturn(null);

        $result = $this->interactor->getGiveawayList([$giveawayId1, $giveawayId2]);
        expect($result)->toBeInstanceOf(GiveawayList::class);
        expect($result)->arrayToHaveCount(1);
        expect($result->findGiveaway($giveawayId2))->toBeNull();
        expect($result->findGiveaway($giveawayId1))->toBe($giveaway);
    }
}
