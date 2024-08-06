<?php

declare(strict_types=1);

namespace AUS\RedirectsTweak\Hook;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TceMainHook
{
    public static function register(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = static::class;
    }

    public function processCmdmap_deleteAction(string $table, int|string $id, array $record, bool &$wasDeleted, DataHandler $dataHandler): void
    {
        if ($table !== 'sys_redirect') {
            return;
        }
        if (!is_int($id)) {
            return;
        }

        // if the redirect is on a deleted page, do not check permissions
        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $page = $pageRepository->getPage_noCheck($record['uid']);
        if ([] === $page) {
            $dataHandler->deleteEl($table, $id, true);
            $wasDeleted = true;
        }
    }
}
