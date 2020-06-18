<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use Marmozist\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor\CreatorProcessor;
use Marmozist\SteamGifts\Application\Proxy\LazyUserProxy;
use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\UseCase\GetUser\Interactor;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class CreatorProcessorTest extends GiveawayProcessorTest
{
    /**
     * @var ObjectProphecy|Interactor
     */
    private ObjectProphecy $getUserInteractor;
    private CreatorProcessor $processor;

    protected function setUp(): void
    {
        $this->getUserInteractor = $this->prophesize(Interactor::class);
        $this->processor = new CreatorProcessor($this->getUserInteractor->reveal());
    }

    public function testProcessGiveaway(): void
    {
        $username = 'Gotman';
        $user = new LazyUserProxy($this->getUserInteractor->reveal(), $username);
        $this->getUserInteractor
            ->getUser($username)
            ->shouldNotBeCalled();

        $content = $this->loadFixture('giveaway.html');
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway($content, $builder);
        expect($builder->build()->getCreator())->equals($user);
    }

    /**
     * @test
     */
    public function processGiveawayWhenNodeNotFound(): void
    {
        $builder = Giveaway::createBuilder();
        $this->processor->processGiveaway('', $builder);
        expect($builder->build()->getCreator())->equals(User::createBuilder()->build());
    }
}
