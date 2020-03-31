<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider;


use Http\Message\RequestFactory;
use Http\Mock\Client;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\UserNotFound;
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
class HttpUserProviderTest extends TestCase
{
    private Client $mockClient;
    private ObjectProphecy $userProcessor;
    private ObjectProphecy $requestFactory;
    private HttpUserProvider $provider;

    protected function setUp(): void
    {
        $this->mockClient = new Client();
        $this->userProcessor = $this->prophesize(UserProcessor::class);
        $this->requestFactory = $this->prophesize(RequestFactory::class);

        $this->provider = new HttpUserProvider($this->mockClient, $this->requestFactory->reveal(), $this->userProcessor->reveal());
    }

    public function testGetUser(): void
    {
        $username = 'Username';
        $builder = User::createBuilder($username);

        $content = '<html>123</html>';
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()->shouldBeCalled()->willReturn($content);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response->getBody()->shouldBeCalled()->willReturn($stream->reveal());

        $this->mockClient->addResponse($response->reveal());

        $this->userProcessor
            ->processUser($content, $builder)
            ->shouldBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/user/$username", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getUser($username))->equals($builder->build());
    }

    /**
     * @test
     */
    public function throwsWhenUserNotFound(): void
    {
        $this->expectException(UserNotFound::class);
        $this->expectExceptionMessage("User 'Username' not found");

        $username = 'Username';
        $builder = User::createBuilder($username);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getStatusCode()->shouldBeCalled()->willReturn(404);
        $this->mockClient->addResponse($response->reveal());

        $this->userProcessor
            ->processUser(Argument::any(), $builder)
            ->shouldNotBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/user/$username", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getUser($username))->equals($builder->build());
    }

    /**
     * @test
     */
    public function throwsWhenUnknownError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unknown http error. Code: 500');

        $username = 'Username';
        $builder = User::createBuilder($username);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getStatusCode()->shouldBeCalled()->willReturn(500);
        $this->mockClient->addResponse($response->reveal());

        $this->userProcessor
            ->processUser(Argument::any(), $builder)
            ->shouldNotBeCalled();

        $request = $this->prophesize(RequestInterface::class)->reveal();
        $this->requestFactory
            ->createRequest("GET", "https://www.steamgifts.com/user/$username", [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
            ])
            ->shouldBeCalled()
            ->willReturn($request);

        expect($this->provider->getUser($username))->equals($builder->build());
    }
}
