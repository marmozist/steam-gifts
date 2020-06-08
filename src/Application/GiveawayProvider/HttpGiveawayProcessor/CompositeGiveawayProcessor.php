<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Component\Giveaway\GiveawayBuilder;
use Webmozart\Assert\Assert;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeGiveawayProcessor implements GiveawayProcessor
{
    /**
     * @var GiveawayProcessor[]
     */
    private array $processors;

    /**
     * CompositeGiveawayProcessor constructor.
     * @param GiveawayProcessor[] $processors
     */
    public function __construct(array $processors)
    {
        Assert::allIsInstanceOf($processors, GiveawayProcessor::class);
        $this->processors = $processors;
    }

    public function processGiveaway(string $content, GiveawayBuilder $builder): void
    {
        foreach ($this->processors as $processor) {
            $processor->processGiveaway($content, $builder);
        }
    }
}
