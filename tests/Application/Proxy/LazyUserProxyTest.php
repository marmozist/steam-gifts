<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\Proxy;


use Marmozist\SteamGifts\Application\Proxy\LazyUserProxy;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LazyUserProxyTest extends TestCase
{
    public function testLazyUserProxy(): void
    {
        $username = 'Gotman';
        $interactor = $this->prophesize(Interactor::class);
        $interactor->getUser($username)
            ->shouldBeCalled()
            ->willReturn(User::createBuilder($username)
                ->setRole(UserRole::Member())
                ->setEnteredGiveaways(102)
                ->build());

        $proxy = new LazyUserProxy($interactor->reveal(), $username);
        expect($proxy)->isInstanceOf(User::class);
        expect($proxy->getName())->same($username);
        expect($proxy->getRole())->equals(UserRole::Member());
        expect($proxy->getLastOnlineAt())->equals(new \DateTimeImmutable('1970-01-01T00:00:00.000000+0000'));
        expect($proxy->getRegisteredAt())->equals(new \DateTimeImmutable('1970-01-01T00:00:00.000000+0000'));
        expect($proxy->getAvatarUrl())->same('');
        expect($proxy->getSteamLink())->same('');
        expect($proxy->getComments())->same(0);
        expect($proxy->getEnteredGiveaways())->same(102);
        expect($proxy->getGiftsWon())->same(0);
        expect($proxy->getGiftsSent())->same(0);
        expect($proxy->getContributorLevel())->same(0.0);
    }

    public function testLazyUserProxyWithoudCallingMethods(): void
    {
        $username = 'Gotman';
        $interactor = $this->prophesize(Interactor::class);
        $interactor->getUser($username)
            ->shouldNotBeCalled();

        $proxy = new LazyUserProxy($interactor->reveal(), $username);
        expect($proxy)->isInstanceOf(User::class);
        expect($proxy->getName())->same($username);
    }

    /**
     * @test
     */
    public function throwsWhenUserNotExists(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('User \'Gotman\' not exists');

        $username = 'Gotman';
        $interactor = $this->prophesize(Interactor::class);
        $interactor->getUser($username)
            ->shouldBeCalled()
            ->willReturn(null);

        $proxy = new LazyUserProxy($interactor->reveal(), $username);
        expect($proxy)->isInstanceOf(User::class);
        $proxy->getRole();
    }
}
