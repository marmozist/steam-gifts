<?php declare(strict_types=1);


namespace Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\Utils\XPathTrait;
use Marmozist\SteamGifts\Component\User\UserBuilder;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ContributorLevelProcessor implements UserProcessor
{
    use XPathTrait;

    public function processUser(string $content, UserBuilder $builder): void
    {
        $expression = "//div[@class='featured__table__row'][4]/div[@class='featured__table__row__right'][1]/span[1]/@data-ui-tooltip";
        $this->load($content);

        if ($this->hasNode($expression)) {
            $cl = $this->prepareContributorLevel($this->getNodeText($expression));
            $builder->setContributorLevel($cl);
        }
    }

    protected function prepareContributorLevel(string $contributeLevelRaw): float
    {
        $json = (array) json_decode($contributeLevelRaw, true, 512);
        if (!array_key_exists('rows', $json)) {
            return 0.0;
        }

        $rows = (array) $json['rows'];
        $row = array_shift($rows) ?? [];
        if (!array_key_exists('columns', $row)) {
            return 0.0;
        }

        $column1 = array_shift($row['columns']) ?? [];
        $column2 = array_shift($row['columns']) ?? [];
        if (!array_key_exists('name', $column2)) {
            return 0.0;
        }

        $contributeLevel = $column2['name'] ?? 0;

        return (float) $contributeLevel;
    }
}
