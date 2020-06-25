<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider;

use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProvider;
use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayNotFound;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpGiveawayProviderTest extends TestCase
{
    private ObjectProphecy $httpClient;
    private ObjectProphecy $giveawayProcessor;
    private HttpGiveawayProvider $provider;

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);
        $this->giveawayProcessor = $this->prophesize(GiveawayProcessor::class);

        $this->provider = new HttpGiveawayProvider($this->httpClient->reveal(), $this->giveawayProcessor->reveal());
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
        $this->httpClient
            ->get('/giveaway/' . $giveawayId . '/')
            ->shouldBeCalled()
            ->willReturn($response);

        $this->giveawayProcessor
            ->processGiveaway($content, $builder)
            ->shouldBeCalled();

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
        $this->httpClient
            ->get('/giveaway/' . $giveawayId . '/')
            ->shouldBeCalled()
            ->willReturn($response);

        $this->giveawayProcessor
            ->processGiveaway($content, $builder)
            ->shouldBeCalled();

        $response2 = $this->prophesize(ResponseInterface::class);
        $response2->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response2->getBody()->shouldBeCalled()->willReturn($stream->reveal());
        $this->httpClient
            ->get('/giveaway/' . $giveawayId . '/need-for-speed-hot-pursuit')
            ->shouldBeCalled()
            ->willReturn($response2);

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
        $this->httpClient
            ->get('/giveaway/' . $giveawayId . '/')
            ->shouldBeCalled()
            ->willReturn($response);

        $this->giveawayProcessor
            ->processGiveaway(Argument::any(), $builder)
            ->shouldNotBeCalled();

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
        $this->httpClient
            ->get('/giveaway/' . $giveawayId . '/')
            ->shouldBeCalled()
            ->willReturn($response);

        $this->giveawayProcessor
            ->processGiveaway(Argument::any(), $builder)
            ->shouldNotBeCalled();

        expect($this->provider->getGiveaway($giveawayId))->equals($builder->build());
    }
}
