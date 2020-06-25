<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\Factory;


use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Application\HttpClient\HttpClientParameters;
use Marmozist\SteamGifts\Application\HttpClient\UserAgentType;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use Marmozist\SteamGifts\Application\UserProvider\Factory\HttpUserProviderFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProvider;
use PHPUnit\Framework\TestCase;
use Buzz\Client as Buzz;
use Http\Adapter\Guzzle6;
use Http\Client\Curl;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpUserProviderFactoryTest extends TestCase
{
    /**
     * @param HttpClientType $clientType
     * @param HttpUserProvider $expectedProvider
     * @dataProvider createProviderExamples
     */
    public function testCreateProvider(HttpClientType $clientType, HttpUserProvider $expectedProvider): void
    {
        $userProcessor = $this->prophesize(UserProcessor::class)->reveal();
        $provider = HttpUserProviderFactory::createProvider($clientType, $userProcessor);

        expect($provider)->equals($expectedProvider);
    }

    /**
     * @return array[]
     */
    public function createProviderExamples(): array
    {
        $parameters = HttpClientParameters::createBuilder()->build();

        return [
            [
                HttpClientType::Guzzle(),
                new HttpUserProvider(new HttpClient(new Guzzle6\Client(), new GuzzleMessageFactory(), $parameters), $this->prophesize(UserProcessor::class)->reveal()),
            ],
            [
                HttpClientType::Buzz(),
                new HttpUserProvider(new HttpClient(new Buzz\FileGetContents(new DiactorosMessageFactory()), new DiactorosMessageFactory(), $parameters), $this->prophesize(UserProcessor::class)->reveal()),
            ],
            [
                HttpClientType::Curl(),
                new HttpUserProvider(new HttpClient(new Curl\Client(), new DiactorosMessageFactory(), $parameters), $this->prophesize(UserProcessor::class)->reveal()),
            ],
        ];
    }

    /**
     * @test
     */
    public function throwsWhenUnknownClientType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported http client type 'Unknown'");

        $userProcessor = $this->prophesize(UserProcessor::class)->reveal();
        HttpUserProviderFactory::createProvider(TestHttpClientType::Unknown(), $userProcessor);
    }
}

/**
 * @method static self Unknown()
 */
class TestHttpClientType extends HttpClientType
{
    const Unknown = 'Unknown';
}
