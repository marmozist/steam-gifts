<?php

namespace Marmozist\Tests\SteamGifts\Application\HttpClient;

use Marmozist\SteamGifts\Application\HttpClient\HttpClientParameters;
use Marmozist\SteamGifts\Application\HttpClient\UserAgentType;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpClientParametersBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $builder = HttpClientParameters::createBuilder();
        $builder
            ->setUserAgent(UserAgentType::ChromeV80Linux())
            ->setBaseUri('custom');

        $parameters = $builder->build();
        expect($parameters)->toBeInstanceOf(HttpClientParameters::class);
        expect($parameters->getBaseUri())->toBe('custom');
        expect($parameters->getUserAgent())->toEqual(UserAgentType::ChromeV80Linux());
    }

    /**
     * @test
     */
    public function buildsWithoutSettersCalling(): void
    {
        $parameters = HttpClientParameters::createBuilder()->build();
        expect($parameters)->toBeInstanceOf(HttpClientParameters::class);
        expect($parameters->getBaseUri())->toBe('https://www.steamgifts.com');
        expect($parameters->getUserAgent())->toEqual(UserAgentType::ChromeV80Linux());
    }
}
