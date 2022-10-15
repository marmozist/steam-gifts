<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider;


use Marmozist\SteamGifts\Application\UserProvider\InMemoryUserProvider;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class InMemoryUserProviderTest extends TestCase
{
    private InMemoryUserProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new InMemoryUserProvider();
    }

    public function testGetUser(): void
    {
        $username = 'Username';
        expect($this->provider->getUser($username)->getName())->toBe($username);
    }
}
