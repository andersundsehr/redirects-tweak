<?php

declare(strict_types=1);

namespace AUS\RedirectsTweak\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RedirectCacheService extends \TYPO3\CMS\Redirects\Service\RedirectCacheService
{
    /**
     * @noinspection PhpMissingParentConstructorInspection
     * @throws NoSuchCacheException
     */
    public function __construct(CacheManager $cacheManager = null)
    {
        $cacheManager = $cacheManager ?? GeneralUtility::makeInstance(CacheManager::class);
        $this->cache = $cacheManager->getCache('andersundsehr_redirects_tweak');
    }
}
