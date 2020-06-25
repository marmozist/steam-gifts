<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\HttpClient;


use Http\Client\HttpClient as BaseHttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpClient
{
    private BaseHttpClient $httpClient;
    private RequestFactory $requestFactory;
    private HttpClientParameters $parameters;

    public function __construct(
        BaseHttpClient $httpClient,
        RequestFactory $requestFactory,
        HttpClientParameters $parameters
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->parameters = $parameters;
    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function get(string $uri): ResponseInterface
    {
        $request = $this->requestFactory->createRequest('GET', $this->parameters->getBaseUri() . $uri, [
            'User-Agent' => $this->parameters->getUserAgent()->getValue()
        ]);

        return $this->httpClient->sendRequest($request);
    }
}
