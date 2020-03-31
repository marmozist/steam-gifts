<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\CommentsProcessor;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CommentsProcessorTest extends UserProcessorTest
{
    private CommentsProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new CommentsProcessor();
    }

    public function testProcessUser(): void
    {
        $content = $this->loadFixture('user.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getComments())->same(37);
    }

    /**
     * @test
     */
    public function processUserWhenNodeNotFound(): void
    {
        $builder = User::createBuilder();
        $this->processor->processUser('', $builder);
        expect($builder->build()->getComments())->same(0);
    }
}
