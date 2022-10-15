<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\Proxy;


use Marmozist\SteamGifts\Application\Proxy\LazyUserProxy;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LazyUserProxyTest extends TestCase
{
    use ProphecyTrait;

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
        expect($proxy)->toBeInstanceOf(User::class);
        expect($proxy->getName())->toBe($username);
        expect($proxy->getRole())->toEqual(UserRole::Member());
        expect($proxy->getLastOnlineAt())->toEqual(new \DateTimeImmutable('1970-01-01T00:00:00.000000+0000'));
        expect($proxy->getRegisteredAt())->toEqual(new \DateTimeImmutable('1970-01-01T00:00:00.000000+0000'));
        expect($proxy->getAvatarUrl())->toBe('');
        expect($proxy->getSteamLink())->toBe('');
        expect($proxy->getComments())->toBe(0);
        expect($proxy->getEnteredGiveaways())->toBe(102);
        expect($proxy->getGiftsWon())->toBe(0);
        expect($proxy->getGiftsSent())->toBe(0);
        expect($proxy->getContributorLevel())->toBe(0.0);
    }

    public function testLazyUserProxyWithoudCallingMethods(): void
    {
        $username = 'Gotman';
        $interactor = $this->prophesize(Interactor::class);
        $interactor->getUser($username)
            ->shouldNotBeCalled();

        $proxy = new LazyUserProxy($interactor->reveal(), $username);
        expect($proxy)->toBeInstanceOf(User::class);
        expect($proxy->getName())->toBe($username);
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
        expect($proxy)->toBeInstanceOf(User::class);
        $proxy->getRole();
    }
}
