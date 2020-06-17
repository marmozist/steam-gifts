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
class GiveawayBuilderTest extends TestCase
{
    public function testBuilder(): void
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

        $builder = Giveaway::createBuilder($id);
        $builder
            ->setId($id)
            ->setName($name)
            ->setCreator($creator)
            ->setCreatedAt($createdAt)
            ->setFinishedAt($finishedAt)
            ->setSteamLink($steamLink)
            ->setCost($cost)
            ->setCopies($copies)
            ->setLevel($level)
            ->setEntries($entries)
            ->setComments($comments)
            ->setRegionRestricted($regionRestricted)
            ->setGroup($group)
            ->setInviteOnly($inviteOnly)
            ->setWhitelist($whitelist);

        $giveaway = $builder->build();

        expect($giveaway->getId())->same($id);
        expect($giveaway->getName())->same($name);
        expect($giveaway->getCreator())->same($creator);
        expect($giveaway->getCreatedAt())->same($createdAt);
        expect($giveaway->getFinishedAt())->same($finishedAt);
        expect($giveaway->getSteamLink())->same($steamLink);
        expect($giveaway->getCost())->same($cost);
        expect($giveaway->getCopies())->same($copies);
        expect($giveaway->getLevel())->same($level);
        expect($giveaway->getEntries())->same($entries);
        expect($giveaway->getComments())->same($comments);
        expect($giveaway->isRegionRestricted())->same($regionRestricted);
        expect($giveaway->isGroup())->same($group);
        expect($giveaway->isInviteOnly())->same($inviteOnly);
        expect($giveaway->isWhitelist())->same($whitelist);
    }
}
