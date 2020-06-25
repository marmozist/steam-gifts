<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider;


use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\UserNotFound;
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
class HttpUserProviderTest extends TestCase
{
    private ObjectProphecy $httpClient;
    private ObjectProphecy $userProcessor;
    private HttpUserProvider $provider;

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);
        $this->userProcessor = $this->prophesize(UserProcessor::class);

        $this->provider = new HttpUserProvider($this->httpClient->reveal(), $this->userProcessor->reveal());
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
        $this->httpClient
            ->get('/user/' . $username)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->userProcessor
            ->processUser($content, $builder)
            ->shouldBeCalled();

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
        $this->httpClient
            ->get('/user/' . $username)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->userProcessor
            ->processUser(Argument::any(), $builder)
            ->shouldNotBeCalled();

        expect($this->provider->getUser($username))->equals($builder->build());
    }

    /**
     * @test
     */
    public function throwsWhenUnknownError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error 123');
        $this->expectExceptionCode(500);

        $username = 'Username';
        $builder = User::createBuilder($username);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getStatusCode()->shouldBeCalled()->willReturn(500);
        $response->getBody()->shouldBeCalled()->willReturn('Error 123');
        $this->httpClient
            ->get('/user/' . $username)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->userProcessor
            ->processUser(Argument::any(), $builder)
            ->shouldNotBeCalled();

        expect($this->provider->getUser($username))->equals($builder->build());
    }
}
