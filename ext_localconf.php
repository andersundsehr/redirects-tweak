<?php

declare(strict_types=1);

use AUS\RedirectsTweak\Hook\TceMainHook;
use TYPO3\CMS\Core\Cache\Backend\ApcuBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Redirects\Service\RedirectCacheService;

defined('TYPO3') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][RedirectCacheService::class] = [
    'className' => \AUS\RedirectsTweak\Service\RedirectCacheService::class
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['andersundsehr_redirects_tweak'] = [
    'frontend' => VariableFrontend::class,
    'backend' => ApcuBackend::class,
    'groups' => [
        'pages',
    ]
];

TceMainHook::register();
