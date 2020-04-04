<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider;


use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\UserNotFound;
use Marmozist\SteamGifts\UseCase\GetUser\UserProvider;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpUserProvider implements UserProvider
{
    private HttpClient $httpClient;
    private RequestFactory $requestFactory;
    private UserProcessor $userProcessor;

    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory, UserProcessor $userProcessor)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->userProcessor = $userProcessor;
    }

    /**
     * @param string $username
     * @return User
     * @throws UserNotFound
     * @throws ClientExceptionInterface
     */
    public function getUser(string $username): User
    {
        $request = $this->requestFactory->createRequest("GET", "https://www.steamgifts.com/user/$username", [
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
        ]);
        $response = $this->httpClient->sendRequest($request);
        if (in_array($response->getStatusCode(), [404, 301], true)) {
            throw new UserNotFound("User '$username' not found");
        }

        if ($response->getStatusCode() !== 200) {
            $code = $response->getStatusCode();
            throw new \RuntimeException("Unknown http error. Code: $code", $code);
        }

        $builder = User::createBuilder($username);
        $this->userProcessor->processUser($response->getBody()->getContents(), $builder);

        return $builder->build();
    }
}
