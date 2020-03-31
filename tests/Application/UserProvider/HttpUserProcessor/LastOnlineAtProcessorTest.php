<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\LastOnlineAtProcessor;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LastOnlineAtProcessorTest extends UserProcessorTest
{
    private LastOnlineAtProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new LastOnlineAtProcessor();
    }

    public function testProcessUser(): void
    {
        $content = $this->loadFixture('user.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getLastOnlineAt())->equals((new \DateTimeImmutable('2020-02-28T17:20:17.000000+0000')));
    }

    /**
     * @test
     */
    public function processUserWhenNodeNotFound(): void
    {
        $builder = User::createBuilder();
        $this->processor->processUser('', $builder);
        expect($builder->build()->getLastOnlineAt())->equals((new \DateTimeImmutable())->setTimestamp(0));
    }

    /**
     * @test
     */
    public function processUserWhenUserIsOnline(): void
    {
        $content = $this->loadFixture('user_online.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getLastOnlineAt())->equals((new \DateTimeImmutable())->setTimestamp(2147483647));
    }
}
