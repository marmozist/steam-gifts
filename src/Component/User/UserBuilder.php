<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Component\User;


use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class UserBuilder
{
    private string $name;
    private UserRole $role;
    private DateTimeImmutable $lastOnlineAt;
    private DateTimeImmutable $registeredAt;
    private string $avatarUrl = '';
    private string $steamLink = '';
    private int $comments = 0;
    private int $enteredGiveaways = 0;
    private int $giftsWon = 0;
    private int $giftsSent = 0;
    private float $contributorLevel = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build(): User
    {
        return new User(
            $this->name,
            $this->role ?? UserRole::None(),
            $this->lastOnlineAt ?? (new DateTimeImmutable())->setTimestamp(0),
            $this->registeredAt ?? (new DateTimeImmutable())->setTimestamp(0),
            $this->avatarUrl,
            $this->steamLink,
            $this->comments,
            $this->enteredGiveaways,
            $this->giftsWon,
            $this->giftsSent,
            $this->contributorLevel
        );
    }

    public function setName(string $name): UserBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function setRole(UserRole $role): UserBuilder
    {
        $this->role = $role;

        return $this;
    }

    public function setLastOnlineAt(DateTimeImmutable $lastOnlineAt): UserBuilder
    {
        $this->lastOnlineAt = $lastOnlineAt;

        return $this;
    }

    public function setRegisteredAt(DateTimeImmutable $registeredAt): UserBuilder
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function setAvatarUrl(string $avatarUrl): UserBuilder
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function setSteamLink(string $steamLink): UserBuilder
    {
        $this->steamLink = $steamLink;

        return $this;
    }

    public function setComments(int $comments): UserBuilder
    {
        $this->comments = $comments;

        return $this;
    }

    public function setEnteredGiveaways(int $enteredGiveaways): UserBuilder
    {
        $this->enteredGiveaways = $enteredGiveaways;

        return $this;
    }

    public function setGiftsWon(int $giftsWon): UserBuilder
    {
        $this->giftsWon = $giftsWon;

        return $this;
    }

    public function setGiftsSent(int $giftsSent): UserBuilder
    {
        $this->giftsSent = $giftsSent;

        return $this;
    }

    public function setContributorLevel(float $contributorLevel): UserBuilder
    {
        $this->contributorLevel = $contributorLevel;

        return $this;
    }
}
