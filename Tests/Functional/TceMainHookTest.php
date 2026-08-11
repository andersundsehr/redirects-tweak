<?php

declare(strict_types=1);

namespace AUS\RedirectsTweak\Tests\Functional;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class TceMainHookTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'redirects',
    ];

    protected array $testExtensionsToLoad = [
        'andersundsehr/redirects-tweak',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/be_users.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/pages.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/sys_redirect.csv');
        $this->setUpBackendUser(1);
    }

    #[Test]
    public function redirectCanBeDeletedInSeparateOperationAfterPageDeletion(): void
    {
        $this->processCommands([
            'pages' => [
                13 => ['delete' => 1],
            ],
        ]);

        self::assertTrue($this->recordExists('pages', 13));
        self::assertTrue($this->recordIsDeleted('pages', 13));
        self::assertTrue($this->recordExists('sys_redirect', 13));
        self::assertTrue($this->recordIsDeleted('sys_redirect', 13));

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_redirect')
            ->update('sys_redirect', ['deleted' => 0], ['uid' => 13]);

        self::assertFalse($this->recordIsDeleted('sys_redirect', 13));

        $this->processCommands([
            'sys_redirect' => [
                13 => ['delete' => 1],
            ],
        ]);

        self::assertTrue($this->recordExists('sys_redirect', 13));
        self::assertTrue($this->recordIsDeleted('sys_redirect', 13));
    }

    /**
     * @param array<string, array<int, array<string, int>>> $commands
     */
    private function processCommands(array $commands): void
    {
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commands);
        $dataHandler->process_cmdmap();
    }

    private function recordExists(string $table, int $uid): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return (bool)$queryBuilder
            ->count('uid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT),
                ),
            )
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @throws Exception
     */
    private function recordIsDeleted(string $table, int $uid): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder
            ->select('deleted')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT),
                ),
            )
            ->executeQuery()
            ->fetchOne() === 1;
    }
}
