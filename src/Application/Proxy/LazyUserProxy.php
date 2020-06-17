<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\Proxy;


use Marmozist\SteamGifts\Component\User\User;
use Marmozist\SteamGifts\Component\User\UserRole;
use Marmozist\SteamGifts\UseCase\GetUser;
use DateTimeImmutable;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class LazyUserProxy extends User
{
    private GetUser\Interactor $interactor;
    private string $username;
    private bool $loaded = false;

    public function __construct(GetUser\Interactor $interactor, string $username)
    {
        $this->interactor = $interactor;
        $this->username = $username;

        $date = (new DateTimeImmutable())->setTimestamp(0);
        parent::__construct($username, UserRole::None(), $date, $date, '', '', 0, 0, 0, 0, 0);
    }

    public function getRole(): UserRole
    {
        $this->loadUser();

        return parent::getRole();
    }

    public function getLastOnlineAt(): DateTimeImmutable
    {
        $this->loadUser();

        return parent::getLastOnlineAt();
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        $this->loadUser();

        return parent::getRegisteredAt();
    }

    public function getAvatarUrl(): string
    {
        $this->loadUser();

        return parent::getAvatarUrl();
    }

    public function getSteamLink(): string
    {
        $this->loadUser();

        return parent::getSteamLink();
    }

    public function getComments(): int
    {
        $this->loadUser();

        return parent::getComments();
    }

    public function getEnteredGiveaways(): int
    {
        $this->loadUser();

        return parent::getEnteredGiveaways();
    }

    public function getGiftsWon(): int
    {
        $this->loadUser();

        return parent::getGiftsWon();
    }

    public function getGiftsSent(): int
    {
        $this->loadUser();

        return parent::getGiftsSent();
    }

    public function getContributorLevel(): float
    {
        $this->loadUser();

        return parent::getContributorLevel();
    }

    private function loadUser(): void
    {
        if ($this->loaded) {
            return;
        }

        /*** @var User $user */
        $user = $this->interactor->getUser($this->username);
        if ($user === null) {
            throw new \InvalidArgumentException(sprintf('User \'%s\' not exists', $this->username));
        }

        $this->loaded = true;
        parent::__construct(
            $user->getName(),
            $user->getRole(),
            $user->getRegisteredAt(),
            $user->getLastOnlineAt(),
            $user->getAvatarUrl(),
            $user->getSteamLink(),
            $user->getComments(),
            $user->getEnteredGiveaways(),
            $user->getGiftsWon(),
            $user->getGiftsSent(),
            $user->getContributorLevel()
        );
    }
}
