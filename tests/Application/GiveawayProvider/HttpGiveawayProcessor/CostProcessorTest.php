<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CostProcessor;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CostProcessorTest extends GiveawayProcessorTest
{
    private CostProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new CostProcessor();
    }

    public function testProcessGiveaway(): void
    {
        $content = $this->loadFixture('giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCost())->same(15);
    }

    /**
     * @test
     */
    public function processGiveawayWhenGiveawayMultipleCopies(): void
    {
        $content = $this->loadFixture('multiple_giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCost())->same(2);
    }

    /**
     * @test
     */
    public function processGiveawayWhenNodeNotFound(): void
    {
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway('', $builder);
        expect($builder->build()->getCost())->same(0);
    }
}
