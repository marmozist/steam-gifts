<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts;


use Http\Message\MessageFactory\DiactorosMessageFactory;
use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\SteamGifts\UseCase\GetUserList\UserList;
use Marmozist\Tests\SteamGifts\Helper\ClientHelper;
use PHPUnit\Framework\TestCase;
use Buzz\Client as Buzz;
use Http\Adapter\Guzzle6;
use Http\Client\Curl;
use Http\Client\HttpClient;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 *
 * @coversNothing
 */
class GetUserListTest extends TestCase
{
    /**
     * @param HttpClient $httpClient
     * @dataProvider httpClientExamples
     */
    public function testGetUserList(HttpClient $httpClient): void
    {
        $client = ClientHelper::createReplayClient($httpClient);
        $userList = $client->getUserList(['Gotman', 'Undefined123']);
        expect($userList)->isInstanceOf(UserList::class);
        expect($userList)->count(1);
        expect($userList->findUser('Undefined123'))->null();

        /** @var User $user */
        $user = $userList->findUser('Gotman');
        expect($user)->isInstanceOf(User::class);
        expect($user->getName())->same('Gotman');
        expect($user->getRole())->equals(UserRole::Member());
        expect($user->getLastOnlineAt())->equals(new \DateTimeImmutable('2020-03-21T09:27:50.000000+0000'));
        expect($user->getRegisteredAt())->equals(new \DateTimeImmutable('2017-01-16T15:27:13.000000+0000'));
        expect($user->getAvatarUrl())->same('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/0c/0c6d0b40c4dfeb935c67242eb50b2148f933ebde_full.jpg');
        expect($user->getSteamLink())->same('https://steamcommunity.com/profiles/76561198116076000');
        expect($user->getComments())->same(37);
        expect($user->getEnteredGiveaways())->same(155876);
        expect($user->getGiftsWon())->same(714);
        expect($user->getGiftsSent())->same(51);
        expect($user->getContributorLevel())->same(4.37);
    }

    /**
     * @return array[]
     */
    public function httpClientExamples(): array
    {
        return [
            [new Guzzle6\Client()],
            [new Curl\Client()],
            [new Buzz\FileGetContents(new DiactorosMessageFactory())],
        ];
    }
}
