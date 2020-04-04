<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\Factory;


use MyCLabs\Enum\Enum;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 *
 * @method static self Curl()
 * @method static self Guzzle()
 * @method static self Buzz()
 */
class HttpClientType extends Enum
{
    const Curl = 'Curl';
    const Guzzle = 'Guzzle';
    const Buzz = 'Buzz';
}
