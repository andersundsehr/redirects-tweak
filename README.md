# Redirects Tweak

This TYPO3 extension provides:

- A dedicated cache for redirects, keeping them separate from the page cache.
  The default backend is `SimpleFileBackend`.
- The ability to delete orphaned redirects whose related page has been deleted.
  This deletion does not check permissions on the storage page.

The redirect cache backend can be configured:
```php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['andersundsehr_redirects_tweak']['backend']
    = \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;
```
