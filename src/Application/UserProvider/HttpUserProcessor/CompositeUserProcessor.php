<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Component\User\UserBuilder;
use Webmozart\Assert\Assert;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CompositeUserProcessor implements UserProcessor
{
    /**
     * @var UserProcessor[]
     */
    private array $processors;

    /**
     * CompositeUserProcessor constructor.
     * @param UserProcessor[] $processors
     */
    public function __construct(array $processors)
    {
        Assert::allIsInstanceOf($processors, UserProcessor::class);
        $this->processors = $processors;
    }

    public function processUser(string $content, UserBuilder $builder): void
    {
        foreach ($this->processors as $processor) {
            $processor->processUser($content, $builder);
        }
    }
}
