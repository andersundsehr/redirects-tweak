This TYPO3 extension offers two functionalities.
Firstly, an extra cache is configured so that redirects are no longer stored in the page cache.
in addition, it is now possible to delete entries in the backend module of the redirects which are on deleted pages. the access rights on the storage page are not checked.

by default the extension uses the APCu cache, this configuration can be easily adjusted in the caching framework:

```PHP
// choose your favourite cache backend
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['andersundsehr-redirects-tweak']['backend'] = SimpleFileBackend
```
