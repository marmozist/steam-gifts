<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;

use DOMXPath;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
trait XPathTrait
{
    private DOMXPath $xpath;

    public function hasNode(string $expression): bool
    {
        $query = $this->xpath->query($expression);

        return ($query === false) ? false : $query->count() > 0;
    }

    public function getNodeText(string $expression): string
    {
        if (!$this->hasNode($expression)) {
            return '';
        }

        $query = $this->xpath->query($expression);
        $node = ($query !== false) ? $query->item(0) : null;

        return ($node !== null) ? $node->textContent : '';
    }

    public function load(string $content): void
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $this->xpath = new \DOMXPath($doc);
    }
}
