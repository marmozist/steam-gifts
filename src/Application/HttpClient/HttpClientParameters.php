<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\HttpClient;


/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpClientParameters
{
    private string $baseUri;
    private UserAgentType $userAgent;

    public function __construct(string $baseUri, UserAgentType $userAgent)
    {
        $this->baseUri = $baseUri;
        $this->userAgent = $userAgent;
    }

    public static function createBuilder(): HttpClientParametersBuilder
    {
        return new HttpClientParametersBuilder();
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getUserAgent(): UserAgentType
    {
        return $this->userAgent;
    }
}
