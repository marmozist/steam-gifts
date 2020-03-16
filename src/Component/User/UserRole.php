<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Component\User;


use MyCLabs\Enum\Enum;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 *
 * @method static self None()
 * @method static self Guest()
 * @method static self Member()
 * @method static self Bundler()
 * @method static self GameDeveloper()
 * @method static self Support()
 * @method static self Moderator()
 * @method static self SuperMod()
 * @method static self Admin()
 */
class UserRole extends Enum
{
    private const None = 'None';
    private const Guest = 'Guest';
    private const Member = 'Member';
    private const Bundler = 'Bundler';
    private const GameDeveloper = 'GameDeveloper';
    private const Support = 'Support';
    private const Moderator = 'Moderator';
    private const SuperMod = 'SuperMod';
    private const Admin = 'Admin';
}
