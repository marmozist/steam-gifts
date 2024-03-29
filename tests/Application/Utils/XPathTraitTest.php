<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\Utils;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use PHPUnit\Framework\TestCase;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class XPathTraitTest extends TestCase
{
    use XPathTrait;

    public function testTrait(): void
    {
        $this->load('<a b="c"><b c="d"><c d="e">123</c></b></a>');
        expect($this->hasNode("//a[@b='c']/b[@c='d']/c[@d='e']"))->toBeTrue();
        expect($this->getNodeText("//a[@b='c']/b[@c='d']/c[@d='e']"))->toBe('123');
    }

    public function testLoadEmpty(): void
    {
        $this->load('');
        expect($this->hasNode("//a[@b='c']/b[@c='d']/c[@d='e']"))->toBeFalse();
        expect($this->getNodeText("//a[@b='c']/b[@c='d']/c[@d='e']"))->toBe('');
    }
}
