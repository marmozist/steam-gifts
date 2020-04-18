<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Component\Giveaway;


use Marmozist\SteamGifts\Component\User\User;
use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class Giveaway
{
    private string $id;
    private string $name;
    private User $creator;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $finishedAd;
    private string $steamLink;
    private int $cost;
    private int $copies;
    private int $level;
    private int $entries;
    private int $comments;
    private bool $regionRestricted;
    private bool $group;
    private bool $inviteOnly;
    private bool $whitelist;

    public function __construct(
        string $id,
        string $name,
        User $creator,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $finishedAd,
        string $steamLink,
        int $cost,
        int $copies,
        int $level,
        int $entries,
        int $comments,
        bool $regionRestricted,
        bool $group,
        bool $inviteOnly,
        bool $whitelist
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->creator = $creator;
        $this->createdAt = $createdAt;
        $this->finishedAd = $finishedAd;
        $this->steamLink = $steamLink;
        $this->cost = $cost;
        $this->copies = $copies;
        $this->level = $level;
        $this->entries = $entries;
        $this->comments = $comments;
        $this->regionRestricted = $regionRestricted;
        $this->group = $group;
        $this->inviteOnly = $inviteOnly;
        $this->whitelist = $whitelist;
    }

    public static function createBuilder(string $id = ''): GiveawayBuilder
    {
        return new GiveawayBuilder($id);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFinishedAd(): DateTimeImmutable
    {
        return $this->finishedAd;
    }

    public function getSteamLink(): string
    {
        return $this->steamLink;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getCopies(): int
    {
        return $this->copies;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getEntries(): int
    {
        return $this->entries;
    }

    public function getComments(): int
    {
        return $this->comments;
    }

    public function isRegionRestricted(): bool
    {
        return $this->regionRestricted;
    }

    public function isGroup(): bool
    {
        return $this->group;
    }

    public function isInviteOnly(): bool
    {
        return $this->inviteOnly;
    }

    public function isWhitelist(): bool
    {
        return $this->whitelist;
    }
}
