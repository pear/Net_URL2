# Net_URL2

Class for parsing and handling URL. Provides parsing of URLs into their constituent parts (scheme, host, path etc.),
URL generation, and resolving of relative URLs.

[![Build Status](https://travis-ci.org/pear/Net_URL2.png)](https://travis-ci.org/pear/Net_URL2)

This package is [Pear Net_URL2] and has been migrated from [Pear SVN]

Please report all new issues via the [PEAR bug tracker].

On Packagist as [pear/net_url2].

[Pear Net_URL2]: http://pear.php.net/package/Net_URL2
[Pear SVN]: https://svn.php.net/repository/pear/packages/Net_URL2
[PEAR bug tracker]: https://pear.php.net/bugs/search.php?cmd=display&package_name%5B%5D=Net_URL2
[pear/net_url2]: https://packagist.org/packages/pear/net_url2

## Testing, Packaging and Installing (Pear)

To test, run either

    $ phpunit tests/

  or

    $ pear run-tests -r

To build, simply

    $ pear package

To install from scratch

    $ pear install package.xml

To upgrade

    $ pear upgrade -f package.xml
