<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Component\User;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class UserBuilderTest extends TestCase
{
    public function testBuilder(): void
    {
        $name = 'name';
        $role = UserRole::Guest();
        $lastOnlineAt = new DateTimeImmutable('-1 hour');
        $registeredAt = new DateTimeImmutable('-1 day');
        $avatarUrl = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/00/0000000000000000000000000000000000000000_full.jpg';
        $steamLink = 'https://steamcommunity.com/profiles/00000000000000000';
        $comments = 23;
        $enteredGiveaways = 34;
        $giftsWon = 45;
        $giftsSent = 56;
        $contributorLevel = 7.23;

        $builder = User::createBuilder($name);
        $builder
            ->setRole($role)
            ->setLastOnlineAt($lastOnlineAt)
            ->setRegisteredAt($registeredAt)
            ->setAvatarUrl($avatarUrl)
            ->setSteamLink($steamLink)
            ->setComments($comments)
            ->setEnteredGiveaways($enteredGiveaways)
            ->setGiftsWon($giftsWon)
            ->setGiftsSent($giftsSent)
            ->setContributorLevel($contributorLevel);

        $user = $builder->build();
        
        expect($user->getName())->toBe($name);
        expect($user->getRole())->toBe($role);
        expect($user->getLastOnlineAt())->toBe($lastOnlineAt);
        expect($user->getRegisteredAt())->toBe($registeredAt);
        expect($user->getAvatarUrl())->toBe($avatarUrl);
        expect($user->getSteamLink())->toBe($steamLink);
        expect($user->getComments())->toBe($comments);
        expect($user->getEnteredGiveaways())->toBe($enteredGiveaways);
        expect($user->getGiftsWon())->toBe($giftsWon);
        expect($user->getGiftsSent())->toBe($giftsSent);
        expect($user->getContributorLevel())->toBe($contributorLevel);
    }
}
