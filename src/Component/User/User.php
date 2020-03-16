<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Component\User;


use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class User
{
    private string $name;
    private UserRole $role;
    private DateTimeImmutable $lastOnlineAt;
    private DateTimeImmutable $registeredAt;
    private string $avatarUrl;
    private string $steamLink;
    private int $comments;
    private int $enteredGiveaways;
    private int $giftsWon;
    private int $giftsSent;
    private float $contributorLevel;

    public function __construct(
        string $name,
        UserRole $role,
        DateTimeImmutable $lastOnlineAt,
        DateTimeImmutable $registeredAt,
        string $avatarUrl,
        string $steamLink,
        int $comments,
        int $enteredGiveaways,
        int $giftsWon,
        int $giftsSent,
        float $contributorLevel
    ) {
        $this->name = $name;
        $this->role = $role;
        $this->lastOnlineAt = $lastOnlineAt;
        $this->registeredAt = $registeredAt;
        $this->avatarUrl = $avatarUrl;
        $this->steamLink = $steamLink;
        $this->comments = $comments;
        $this->enteredGiveaways = $enteredGiveaways;
        $this->giftsWon = $giftsWon;
        $this->giftsSent = $giftsSent;
        $this->contributorLevel = $contributorLevel;
    }

    public static function createBuilder(string $name = ''): UserBuilder
    {
        return new UserBuilder($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getLastOnlineAt(): DateTimeImmutable
    {
        return $this->lastOnlineAt;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function getSteamLink(): string
    {
        return $this->steamLink;
    }

    public function getComments(): int
    {
        return $this->comments;
    }

    public function getEnteredGiveaways(): int
    {
        return $this->enteredGiveaways;
    }

    public function getGiftsWon(): int
    {
        return $this->giftsWon;
    }

    public function getGiftsSent(): int
    {
        return $this->giftsSent;
    }

    public function getContributorLevel(): float
    {
        return $this->contributorLevel;
    }
}
