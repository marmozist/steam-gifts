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
        expect($list)->arrayToHaveCount(2);
        expect($list->findGiveaway('O8NIM'))->toBe($giveaway1);
        expect($list->findGiveaway('I3mBK'))->toBe($giveaway2);
        expect($list->findGiveaway('S4sRN'))->toBeNull();
        expect($list->getIterator())->toBe($iterator);
    }

    public function testEmptyGiveawayList(): void
    {
        $list = new GiveawayList(new \ArrayIterator([]));
        expect($list)->arrayToHaveCount(0);
        expect($list->findGiveaway('O8NIM'))->toBeNull();
        expect($list->findGiveaway('I3mBK'))->toBeNull();
        expect($list->findGiveaway('S4sRN'))->toBeNull();
    }
}
