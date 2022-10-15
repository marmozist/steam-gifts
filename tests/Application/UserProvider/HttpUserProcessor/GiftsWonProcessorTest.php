<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\GiftsWonProcessor;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiftsWonProcessorTest extends UserProcessorTest
{
    private GiftsWonProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new GiftsWonProcessor();
    }

    public function testProcessUser(): void
    {
        $content = $this->loadFixture('user.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getGiftsWon())->toBe(714);
    }

    /**
     * @test
     */
    public function processUserWhenNodeNotFound(): void
    {
        $builder = User::createBuilder();
        $this->processor->processUser('', $builder);
        expect($builder->build()->getGiftsWon())->toBe(0);
    }
}
