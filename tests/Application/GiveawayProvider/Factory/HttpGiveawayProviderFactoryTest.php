<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\Factory;


use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\Factory\HttpGiveawayProviderFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProvider;
use Marmozist\SteamGifts\Application\Utils\Http\HttpClientType;
use PHPUnit\Framework\TestCase;
use Buzz\Client as Buzz;
use Http\Adapter\Guzzle6;
use Http\Client\Curl;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpGiveawayProviderFactoryTest extends TestCase
{
    /**
     * @param HttpClientType $clientType
     * @param HttpGiveawayProvider $expectedProvider
     * @dataProvider createProviderExamples
     */
    public function testCreateProvider(HttpClientType $clientType, HttpGiveawayProvider $expectedProvider): void
    {
        $giveawayProcessor = $this->prophesize(GiveawayProcessor::class)->reveal();
        $provider = HttpGiveawayProviderFactory::createProvider($clientType, $giveawayProcessor);

        expect($provider)->equals($expectedProvider);
    }

    /**
     * @return array[]
     */
    public function createProviderExamples(): array
    {
        return [
            [
                HttpClientType::Guzzle(),
                new HttpGiveawayProvider(new Guzzle6\Client(), new GuzzleMessageFactory(), $this->prophesize(GiveawayProcessor::class)->reveal()),
            ],
            [
                HttpClientType::Buzz(),
                new HttpGiveawayProvider(new Buzz\FileGetContents(new DiactorosMessageFactory()), new DiactorosMessageFactory(), $this->prophesize(GiveawayProcessor::class)->reveal()),
            ],
            [
                HttpClientType::Curl(),
                new HttpGiveawayProvider(new Curl\Client(), new DiactorosMessageFactory(), $this->prophesize(GiveawayProcessor::class)->reveal()),
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

        $giveawayProcessor = $this->prophesize(GiveawayProcessor::class)->reveal();
        HttpGiveawayProviderFactory::createProvider(
            TestHttpClientType::Unknown(), $giveawayProcessor);
    }
}

/**
 * @method static self Unknown()
 */
class TestHttpClientType extends HttpClientType
{
    const Unknown = 'Unknown';
}
