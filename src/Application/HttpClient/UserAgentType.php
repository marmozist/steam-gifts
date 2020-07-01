<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\HttpClient;


use MyCLabs\Enum\Enum;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 *
 * @method static self FirefoxV73MacOS10()
 * @method static self FirefoxV74Linux()
 * @method static self FirefoxV75WindowsV81()
 * @method static self FirefoxV73WindowsV10()
 * @method static self ChromeV79MacOSV10()
 * @method static self ChromeV80Linux()
 * @method static self ChromeV79WindowsV81()
 * @method static self ChromeV80WindowsV10()
 */
class UserAgentType extends Enum
{
    const FirefoxV73MacOS10 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:73.0) Gecko/20100101 Firefox/73.0';
    const FirefoxV74Linux = 'Mozilla/5.0 (X11; Linux i686; rv:74.0) Gecko/20100101 Firefox/74.0';
    const FirefoxV75WindowsV81 = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:75.0) Gecko/20100101 Firefox/75.0';
    const FirefoxV73WindowsV10 = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:73.1) Gecko/20100101 Firefox/73.1';
    const ChromeV79MacOSV10 =   'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.121 Safari/537.36';
    const ChromeV80Linux = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36';
    const ChromeV79WindowsV81 = 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.141 Safari/537.36';
    const ChromeV80WindowsV10 = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.83 Safari/537.36';
}
