<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Component\Giveaway;


use Marmozist\SteamGifts\Component\User\User;
use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiveawayBuilder
{
    private string $id;
    private string $name = '';
    private User $creator;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $finishedAd;
    private string $steamLink = '';
    private int $cost = 0;
    private int $copies = 0;
    private int $level = 0;
    private int $entries = 0;
    private int $comments = 0;
    private bool $regionRestricted = false;
    private bool $group = false;
    private bool $inviteOnly = false;
    private bool $whitelist = false;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function build(): Giveaway
    {
        return new Giveaway(
            $this->id,
            $this->name,
            $this->creator ?? User::createBuilder()->build(),
            $this->createdAt ?? (new DateTimeImmutable())->setTimestamp(0),
            $this->finishedAd ?? (new DateTimeImmutable())->setTimestamp(0),
            $this->steamLink,
            $this->cost,
            $this->copies,
            $this->level,
            $this->entries,
            $this->comments,
            $this->regionRestricted,
            $this->group,
            $this->inviteOnly,
            $this->whitelist
        );
    }

    public function setId(string $id): GiveawayBuilder
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name): GiveawayBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function setCreator(User $creator): GiveawayBuilder
    {
        $this->creator = $creator;

        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): GiveawayBuilder
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setFinishedAd(DateTimeImmutable $finishedAd): GiveawayBuilder
    {
        $this->finishedAd = $finishedAd;

        return $this;
    }

    public function setSteamLink(string $steamLink): GiveawayBuilder
    {
        $this->steamLink = $steamLink;

        return $this;
    }

    public function setCost(int $cost): GiveawayBuilder
    {
        $this->cost = $cost;

        return $this;
    }

    public function setCopies(int $copies): GiveawayBuilder
    {
        $this->copies = $copies;

        return $this;
    }

    public function setLevel(int $level): GiveawayBuilder
    {
        $this->level = $level;

        return $this;
    }

    public function setEntries(int $entries): GiveawayBuilder
    {
        $this->entries = $entries;

        return $this;
    }

    public function setComments(int $comments): GiveawayBuilder
    {
        $this->comments = $comments;

        return $this;
    }

    public function setRegionRestricted(bool $regionRestricted): GiveawayBuilder
    {
        $this->regionRestricted = $regionRestricted;

        return $this;
    }

    public function setGroup(bool $group): GiveawayBuilder
    {
        $this->group = $group;

        return $this;
    }

    public function setInviteOnly(bool $inviteOnly): GiveawayBuilder
    {
        $this->inviteOnly = $inviteOnly;

        return $this;
    }

    public function setWhitelist(bool $whitelist): GiveawayBuilder
    {
        $this->whitelist = $whitelist;

        return $this;
    }
}
