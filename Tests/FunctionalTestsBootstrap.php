<?php

declare(strict_types=1);

$packageRoot = dirname(__DIR__);
$packageDocumentRoot = $packageRoot . '/public';
$applicationDocumentRoot = dirname($packageRoot, 2) . '/public';

if (!is_file($packageDocumentRoot . '/index.php') && is_file($applicationDocumentRoot . '/index.php')) {
    putenv('TYPO3_PATH_ROOT=' . $applicationDocumentRoot);
}

if (getenv('typo3DatabaseDriver') === false) {
    putenv('typo3DatabaseDriver=pdo_sqlite');
}

if (getenv('typo3DatabaseName') === false) {
    putenv('typo3DatabaseName=' . sys_get_temp_dir() . '/redirects-tweak.sqlite');
}

require $packageRoot . '/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTestsBootstrap.php';
