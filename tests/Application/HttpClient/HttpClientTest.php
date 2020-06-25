<?php

namespace Marmozist\Tests\SteamGifts\Application\HttpClient;

use Http\Message\RequestFactory;
use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Application\HttpClient\HttpClientParameters;
use Http\Mock;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpClientTest extends TestCase
{
    private Mock\Client $mockClient;
    private ObjectProphecy $requestFactory;
    private HttpClient $client;

    protected function setUp(): void
    {
        $this->mockClient = new Mock\Client();
        $this->requestFactory = $this->prophesize(RequestFactory::class);
        $this->client = new HttpClient($this->mockClient, $this->requestFactory->reveal(), HttpClientParameters::createBuilder()->build());
    }

    public function testGet(): void
    {
        $response = $this->prophesize(ResponseInterface::class);
        $this->mockClient->addResponse($response->reveal());

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/target/uri/path", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        $this->client->get('/target/uri/path');
    }
}
