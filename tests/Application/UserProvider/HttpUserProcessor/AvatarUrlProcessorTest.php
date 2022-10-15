<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\AvatarUrlProcessor;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class AvatarUrlProcessorTest extends UserProcessorTest
{
    private AvatarUrlProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new AvatarUrlProcessor();
    }

    public function testProcessUser(): void
    {
        $content = $this->loadFixture('user.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getAvatarUrl())->toBe('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/0c/0c6d0b40c4dfeb935c67242eb50b2148f933ebde_full.jpg');
    }

    /**
     * @test
     */
    public function processUserWhenNodeNotFound(): void
    {
        $builder = User::createBuilder();
        $this->processor->processUser('', $builder);
        expect($builder->build()->getAvatarUrl())->toBe('');
    }
}
