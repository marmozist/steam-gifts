<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\UseCase\GetGiveawayList;


use Marmozist\SteamGifts\Component\Giveaway\Giveaway;
use Marmozist\SteamGifts\UseCase\GetGiveawayList\GiveawayList;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class GiveawayListTest extends TestCase
{
    public function testGiveawayList(): void
    {
        $giveaway1 = Giveaway::createBuilder('O8nIm')->build();
        $giveaway2 = Giveaway::createBuilder('I3mBK')->build();

        $generator = static function () use ($giveaway1, $giveaway2): \Generator {
            foreach ([$giveaway1, $giveaway2] as $giveaway) {
                yield $giveaway;
            }
        };

        $iterator = $generator();
        $list = new GiveawayList($iterator);
        expect($list)->count(2);
        expect($list->findGiveaway('O8NIM'))->same($giveaway1);
        expect($list->findGiveaway('I3mBK'))->same($giveaway2);
        expect($list->findGiveaway('S4sRN'))->null();
        expect($list->getIterator())->same($iterator);
    }

    public function testEmptyGiveawayList(): void
    {
        $list = new GiveawayList(new \ArrayIterator([]));
        expect($list)->count(0);
        expect($list->findGiveaway('O8NIM'))->null();
        expect($list->findGiveaway('I3mBK'))->null();
        expect($list->findGiveaway('S4sRN'))->null();
    }
}
