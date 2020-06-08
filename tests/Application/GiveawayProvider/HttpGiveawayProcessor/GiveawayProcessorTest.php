<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\GiveawayProvider\HttpGiveawayProcessor;


use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
abstract class GiveawayProcessorTest extends TestCase
{
    protected function loadFixture(string $name): string
    {
        $content = @file_get_contents(__DIR__ . "/../../../Fixtures/html/$name");
        expect($content)->string();

        return $content === false ? '' : $content;
    }
}
