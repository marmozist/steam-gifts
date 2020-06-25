<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Application\HttpClient\HttpClient;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayNotFound;
use Marmozist\SteamGifts\UseCase\GetGiveaway\GiveawayProvider;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class HttpGiveawayProvider implements GiveawayProvider
{
    private HttpClient $httpClient;
    private GiveawayProcessor $giveawayProcessor;

    public function __construct(HttpClient $httpClient, GiveawayProcessor $giveawayProcessor)
    {
        $this->httpClient = $httpClient;
        $this->giveawayProcessor = $giveawayProcessor;
    }

    /**
     * @param string $giveawayId
     * @return Giveaway
     * @throws GiveawayNotFound
     * @throws ClientExceptionInterface
     */
    public function getGiveaway(string $giveawayId): Giveaway
    {
        $response = $this->httpClient->get("/giveaway/$giveawayId/");
        $location = $response->getHeader('Location')[0] ?? '';
        if ($location !== '/' && $response->getStatusCode() === 301) {
            $response = $this->httpClient->get($location);
        }

        if (in_array($response->getStatusCode(), [404, 301], true)) {
            throw new GiveawayNotFound("Giveaway '$giveawayId' not found");
        }

        if ($response->getStatusCode() !== 200) {
            $code = $response->getStatusCode();
            throw new \RuntimeException("Unknown http error. Code: $code", $code);
        }

        $builder = Giveaway::createBuilder($giveawayId);
        $this->giveawayProcessor->processGiveaway($response->getBody()->getContents(), $builder);

        return $builder->build();
    }
}
