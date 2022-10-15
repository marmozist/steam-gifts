<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Component\Giveaway;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\Component\User\User;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiveawayTest extends TestCase
{
    public function testGiveaway(): void
    {
        $id = 'O8NIm';
        $name = 'Need For Speed: Hot Pursuit';
        $creator = User::createBuilder('Gotman')->build();
        $createdAt = new DateTimeImmutable('-1 hour');
        $finishedAt = new DateTimeImmutable('+1 hour');
        $steamLink = 'https://store.steampowered.com/app/47870/Need_For_Speed_Hot_Pursuit/';
        $cost = 20;
        $copies = 1;
        $level = 3;
        $entries = 212;
        $comments = 2;
        $regionRestricted = true;
        $group = false;
        $inviteOnly = false;
        $whitelist = false;

        $giveaway = new Giveaway(
            $id,
            $name,
            $creator,
            $createdAt ,
            $finishedAt,
            $steamLink,
            $cost,
            $copies,
            $level,
            $entries,
            $comments,
            $regionRestricted,
            $group,
            $inviteOnly,
            $whitelist
        );

        expect($giveaway->getId())->toBe($id);
        expect($giveaway->getName())->toBe($name);
        expect($giveaway->getCreator())->toBe($creator);
        expect($giveaway->getCreatedAt())->toBe($createdAt);
        expect($giveaway->getFinishedAt())->toBe($finishedAt);
        expect($giveaway->getSteamLink())->toBe($steamLink);
        expect($giveaway->getCost())->toBe($cost);
        expect($giveaway->getCopies())->toBe($copies);
        expect($giveaway->getLevel())->toBe($level);
        expect($giveaway->getEntries())->toBe($entries);
        expect($giveaway->getComments())->toBe($comments);
        expect($giveaway->isRegionRestricted())->toBe($regionRestricted);
        expect($giveaway->isGroup())->toBe($group);
        expect($giveaway->isInviteOnly())->toBe($inviteOnly);
        expect($giveaway->isWhitelist())->toBe($whitelist);
    }
}
