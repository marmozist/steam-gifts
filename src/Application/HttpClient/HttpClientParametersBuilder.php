<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\HttpClient;


/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpClientParametersBuilder
{
    private string $baseUri = 'https://www.steamgifts.com';
    private UserAgentType $userAgent;

    public function build(): HttpClientParameters
    {
        return new HttpClientParameters(
            $this->baseUri,
            $this->userAgent ?? UserAgentType::ChromeV80Linux()
        );
    }

    public function setBaseUri(string $baseUri): HttpClientParametersBuilder
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function setUserAgent(UserAgentType $userAgent): HttpClientParametersBuilder
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}
