<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\Factory;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\AvatarUrlProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CommentsProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\ContributorLevelProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CompositeUserProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\EnteredGiveawaysProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\GiftsSentProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\GiftsWonProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\LastOnlineAtProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\NameProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\RegisteredAtProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\RoleProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\SteamLinkProcessor;
use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\UserProcessor;
use Webmozart\Assert\Assert;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeUserProcessorFactory
{
    public static function createProcessor(): CompositeUserProcessor
    {
        $processors = static::getProcessors();
        Assert::allClassExists($processors);

        /** @var UserProcessor[] $instances */
        $instances = array_map(fn(string $class) => new $class(), $processors);
        Assert::allIsInstanceOf($instances, UserProcessor::class);

        return new CompositeUserProcessor($instances);
    }

    /**
     * @return string[]
     */
    protected static function getProcessors(): array
    {
        return [
            AvatarUrlProcessor::class,
            CommentsProcessor::class,
            ContributorLevelProcessor::class,
            EnteredGiveawaysProcessor::class,
            GiftsSentProcessor::class,
            GiftsWonProcessor::class,
            LastOnlineAtProcessor::class,
            NameProcessor::class,
            RegisteredAtProcessor::class,
            RoleProcessor::class,
            SteamLinkProcessor::class,
        ];
    }
}
