<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\Factory;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CommentsProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CompositeGiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CopiesProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CostProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatedAtProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatorProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\EntriesProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\FinishedAtProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\GiveawayProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\LevelProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\NameProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\RegionRestrictedProcessor;
use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\SteamLinkProcessor;
use Marmozist\SteamGifts\UseCase\GetUser;
use Webmozart\Assert\Assert;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeGiveawayProcessorFactory
{
    public static function createProcessor(GetUser\Interactor $interactor): CompositeGiveawayProcessor
    {
        $processors = static::getProcessors();
        Assert::allClassExists($processors);

        $instances = [];
        $creatorProcessorKey = array_search(CreatorProcessor::class, $processors, true);
        if ($creatorProcessorKey !== false) {
            $instances[] = new CreatorProcessor($interactor);
            unset($processors[$creatorProcessorKey]);
        }

        $instances = array_merge($instances, array_map(fn(string $class) => new $class(), $processors));

        Assert::allIsInstanceOf($instances, GiveawayProcessor::class);

        return new CompositeGiveawayProcessor($instances);
    }

    /**
     * @return string[]
     */
    protected static function getProcessors(): array
    {
        return [
            CommentsProcessor::class,
            CopiesProcessor::class,
            CostProcessor::class,
            CreatedAtProcessor::class,
            CreatorProcessor::class,
            EntriesProcessor::class,
            FinishedAtProcessor::class,
            LevelProcessor::class,
            NameProcessor::class,
            RegionRestrictedProcessor::class,
            SteamLinkProcessor::class
        ];
    }
}
