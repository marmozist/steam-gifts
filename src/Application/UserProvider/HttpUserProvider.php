<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider;


use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\UserNotFound;
use Marmozist\SteamGifts\UseCase\GetUser\UserProvider;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpUserProvider implements UserProvider
{
    private HttpClient $httpClient;
    private UserProcessor $userProcessor;

    public function __construct(HttpClient $httpClient, UserProcessor $userProcessor)
    {
        $this->httpClient = $httpClient;
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
        $response = $this->httpClient->get("/user/$username");
        if ($this->isUserFound($response)) {
            $builder = User::createBuilder($username);
            $this->userProcessor->processUser($response->getBody()->getContents(), $builder);

            return $builder->build();
        }

        if ($this->isUserNotFound($response)) {
            throw new UserNotFound("User '$username' not found");
        }

        throw new \RuntimeException((string)$response->getBody(), $response->getStatusCode());
    }

    private function isUserFound(ResponseInterface $response): bool
    {
        return $response->getStatusCode() === 200;
    }

    private function isUserNotFound(ResponseInterface $response): bool
    {
        return in_array($response->getStatusCode(), [404, 301], true);
    }
}
