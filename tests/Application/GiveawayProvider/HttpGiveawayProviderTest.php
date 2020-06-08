<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider;

use Http\Message\RequestFactory;
use Http\Mock\Client;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProvider;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayNotFound;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpGiveawayProviderTest extends TestCase
{
    private Client $mockClient;
    private ObjectProphecy $giveawayProcessor;
    private ObjectProphecy $requestFactory;
    private HttpGiveawayProvider $provider;

    protected function setUp(): void
    {
        $this->mockClient = new Client();
        $this->giveawayProcessor = $this->prophesize(GiveawayProcessor::class);
        $this->requestFactory = $this->prophesize(RequestFactory::class);

        $this->provider = new HttpGiveawayProvider($this->mockClient, $this->requestFactory->reveal(), $this->giveawayProcessor->reveal());
    }

    public function testGetGiveaway(): void
    {
        $giveawayId = 'O8NIm';
        $builder = Giveaway::createBuilder($giveawayId);

        $content = '<html>123</html>';
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()->shouldBeCalled()->willReturn($content);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getHeader('Location')->shouldBeCalled()->willReturn([]);
        $response->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response->getBody()->shouldBeCalled()->willReturn($stream->reveal());

        $this->mockClient->addResponse($response->reveal());

        $this->giveawayProcessor
            ->processGiveaway($content, $builder)
            ->shouldBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/giveaway/$giveawayId/", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getGiveaway($giveawayId))->equals($builder->build());
    }

    /**
     * @test
     */
    public function getsGiveawayWhenMovedPermanently(): void
    {
        $giveawayId = 'O8NIm';
        $builder = Giveaway::createBuilder($giveawayId);

        $content = '<html>123</html>';
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()->shouldBeCalled()->willReturn($content);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getHeader('Location')->shouldBeCalled()->willReturn(['/giveaway/O8NIm/need-for-speed-hot-pursuit']);
        $response->getStatusCode()->shouldBeCalled()->willReturn(301);

        $this->mockClient->addResponse($response->reveal());

        $this->giveawayProcessor
            ->processGiveaway($content, $builder)
            ->shouldBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/giveaway/$giveawayId/", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        $response2 = $this->prophesize(ResponseInterface::class);
        $response2->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response2->getBody()->shouldBeCalled()->willReturn($stream->reveal());

        $this->mockClient->addResponse($response2->reveal());

        $request2 = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/giveaway/$giveawayId/need-for-speed-hot-pursuit", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request2);

        expect($this->provider->getGiveaway($giveawayId))->equals($builder->build());
    }

    /**
     * @test
     */
    public function throwsWhenGiveawayNotFound(): void
    {
        $this->expectException(GiveawayNotFound::class);
        $this->expectExceptionMessage("Giveaway 'O8NIl' not found");

        $giveawayId = 'O8NIl';
        $builder = Giveaway::createBuilder($giveawayId);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getHeader('Location')->shouldBeCalled()->willReturn([]);
        $response->getStatusCode()->shouldBeCalled()->willReturn(404);
        $this->mockClient->addResponse($response->reveal());

        $this->giveawayProcessor
            ->processGiveaway(Argument::any(), $builder)
            ->shouldNotBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/giveaway/$giveawayId/", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getGiveaway($giveawayId))->equals($builder->build());
    }

    /**
     * @test
     */
    public function throwsWhenUnknownError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unknown http error. Code: 500');

        $giveawayId = 'O8NIm';
        $builder = Giveaway::createBuilder($giveawayId);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getHeader('Location')->shouldBeCalled()->willReturn([]);
        $response->getStatusCode()->shouldBeCalled()->willReturn(500);
        $this->mockClient->addResponse($response->reveal());

        $this->giveawayProcessor
            ->processGiveaway(Argument::any(), $builder)
            ->shouldNotBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/giveaway/$giveawayId/", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getGiveaway($giveawayId))->equals($builder->build());
    }
}
