<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CopiesProcessor;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CopiesProcessorTest extends GiveawayProcessorTest
{
    private CopiesProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new CopiesProcessor();
    }

    public function testProcessGiveaway(): void
    {
        $content = $this->loadFixture('multiple_giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCopies())->toBe(10);
    }

    /**
     * @test
     */
    public function processGiveawayWhenGiveawayOneCopy(): void
    {
        $content = $this->loadFixture('giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCopies())->toBe(1);
    }

    /**
     * @test
     */
    public function processGiveawayWhenNodeNotFound(): void
    {
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway('', $builder);
        expect($builder->build()->getCopies())->toBe(0);
    }
}
