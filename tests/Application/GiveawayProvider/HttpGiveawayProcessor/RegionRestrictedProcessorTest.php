<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\RegionRestrictedProcessor;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class RegionRestrictedProcessorTest extends GiveawayProcessorTest
{
    private RegionRestrictedProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new RegionRestrictedProcessor();
    }

    public function testProcessGiveaway(): void
    {
        $content = $this->loadFixture('region_restricted_giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->isRegionRestricted())->toBeTrue();
    }

    /**
     * @test
     */
    public function processGiveawayWhenNodeNotFound(): void
    {
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway('', $builder);
        expect($builder->build()->isRegionRestricted())->toBeFalse();
    }
}
