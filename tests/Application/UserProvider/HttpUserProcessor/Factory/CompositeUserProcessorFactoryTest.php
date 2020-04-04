<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\AvatarUrlProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CommentsProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\ContributorLevelProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CompositeUserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory\CompositeUserProcessorFactory;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\EnteredGiveawaysProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\GiftsSentProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\GiftsWonProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\LastOnlineAtProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\NameProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\RegisteredAtProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\RoleProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\SteamLinkProcessor;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeUserProcessorFactoryTest extends TestCase
{
    public function testCreateProcessor(): void
    {
        $expectedProcessor = new CompositeUserProcessor([
            new AvatarUrlProcessor(),
            new CommentsProcessor(),
            new ContributorLevelProcessor(),
            new EnteredGiveawaysProcessor(),
            new GiftsSentProcessor(),
            new GiftsWonProcessor(),
            new LastOnlineAtProcessor(),
            new NameProcessor(),
            new RegisteredAtProcessor(),
            new RoleProcessor(),
            new SteamLinkProcessor(),
        ]);
        $result = CompositeUserProcessorFactory::createProcessor();
        expect($result)->isInstanceOf(CompositeUserProcessor::class);
        expect($result)->equals($expectedProcessor);
    }
}
