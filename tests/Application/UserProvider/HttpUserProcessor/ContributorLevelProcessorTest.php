<?php declare(strict_types=1);


namespace Marmozist\Tests\SteamGifts\Application\UserProvider\HttpUserProcessor;


use Marmozist\SteamGifts\Application\UserProvider\HttpUserProcessor\ContributorLevelProcessor;
use Marmozist\SteamGifts\Component\User\User;

/**
 * @link    http://github.com/marmozist/steam-gifts
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 * @author  Andrey Gotmanov <gotman.man@gmail.com>
 */
class ContributorLevelProcessorTest extends UserProcessorTest
{
    private ContributorLevelProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new ContributorLevelProcessor();
    }

    public function testProcessUser(): void
    {
        $content = $this->loadFixture('user.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getContributorLevel())->toBe(4.37);
    }

    /**
     * @test
     */
    public function processUserWhenNodeNotFound(): void
    {
        $builder = User::createBuilder();
        $this->processor->processUser('', $builder);
        expect($builder->build()->getContributorLevel())->toBe(0.0);
    }

    /**
     * @test
     */
    public function processUserWhenNodeRowsNotFound(): void
    {
        $content = $this->loadFixture('contributor_level_without_rows.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getContributorLevel())->toBe(0.0);
    }

    /**
     * @test
     */
    public function processUserWhenNodeColumnsNotFound(): void
    {
        $content = $this->loadFixture('contributor_level_without_columns.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getContributorLevel())->toBe(0.0);
    }

    /**
     * @test
     */
    public function processUserWhenNodeNameNotFound(): void
    {
        $content = $this->loadFixture('contributor_level_without_name.html');
        $builder = User::createBuilder();
        $this->processor->processUser($content, $builder);
        expect($builder->build()->getContributorLevel())->toBe(0.0);
    }
}
