<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CommentsProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CompositeGiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CopiesProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CostProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatedAtProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatorProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\EntriesProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory\CompositeGiveawayProcessorFactory;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\FinishedAtProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\LevelProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\NameProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\RegionRestrictedProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\SteamLinkProcessor;
use Marmozist\SteamGifts\UseCase\GetUser;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeGiveawayProcessorFactoryTest extends TestCase
{
    public function testCreateProcessor(): void
    {
        /** @var GetUser\Interactor $interactor */
        $interactor = $this->prophesize(GetUser\Interactor::class)->reveal();

        $expectedProcessor = new CompositeGiveawayProcessor([
            new CreatorProcessor($interactor),
            new CommentsProcessor(),
            new CopiesProcessor(),
            new CostProcessor(),
            new CreatedAtProcessor(),
            new EntriesProcessor(),
            new FinishedAtProcessor(),
            new LevelProcessor(),
            new NameProcessor(),
            new RegionRestrictedProcessor(),
            new SteamLinkProcessor(),
        ]);
        $result = CompositeGiveawayProcessorFactory::createProcessor($interactor);
        expect($result)->isInstanceOf(CompositeGiveawayProcessor::class);
        expect($result)->equals($expectedProcessor);
    }
}
