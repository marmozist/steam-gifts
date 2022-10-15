<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CompositeUserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Component\User\User;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeUserProcessorTest extends UserProcessorTest
{
    use ProphecyTrait;

    private ObjectProphecy $processor1;
    private ObjectProphecy $processor2;
    private CompositeUserProcessor $compositeProcessor;

    protected function setUp(): void
    {
        $this->processor1 = $this->prophesize(UserProcessor::class);
        $this->processor2 = $this->prophesize(UserProcessor::class);
        $this->compositeProcessor = new CompositeUserProcessor([$this->processor1->reveal(), $this->processor2->reveal()]);
    }

    public function testProcessUser(): void
    {
        $content = '<html>123</html>';
        $builder = User::createBuilder();

        $this->processor1->processUser($content, $builder)
            ->shouldBeCalled();

        $this->processor2->processUser($content, $builder)
            ->shouldBeCalled();

        $this->compositeProcessor->processUser($content, $builder);
    }
}
