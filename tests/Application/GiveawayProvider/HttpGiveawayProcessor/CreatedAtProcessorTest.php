<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatedAtProcessor;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CreatedAtProcessorTest extends GiveawayProcessorTest
{
    private CreatedAtProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new CreatedAtProcessor();
    }

    public function testProcessGiveaway(): void
    {
        $content = $this->loadFixture('giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCreatedAt())->toEqual((new \DateTimeImmutable('2017-06-26T19:00:36.000000+0000')));
    }

    /**
     * @test
     */
    public function processGiveawayWhenNodeNotFound(): void
    {
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway('', $builder);
        expect($builder->build()->getCreatedAt())->toEqual((new \DateTimeImmutable())->setTimestamp(0));
    }
}
