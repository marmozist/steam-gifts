<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CompositeGiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeGiveawayProcessorTest extends TestCase
{
    private ObjectProphecy $processor1;
    private ObjectProphecy $processor2;
    private CompositeGiveawayProcessor $compositeProcessor;

    protected function setUp(): void
    {
        $this->processor1 = $this->prophesize(GiveawayProcessor::class);
        $this->processor2 = $this->prophesize(GiveawayProcessor::class);
        $this->compositeProcessor = new CompositeGiveawayProcessor([$this->processor1->reveal(), $this->processor2->reveal()]);
    }

    public function testProcessGiveaway(): void
    {
        $content = '<html>123</html>';
        $builder = Giveaway::createBuilder();

        $this->processor1->processGiveaway($content, $builder)
            ->shouldBeCalled();

        $this->processor2->processGiveaway($content, $builder)
            ->shouldBeCalled();

        $this->compositeProcessor->processGiveaway($content, $builder);
    }
}
