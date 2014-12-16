Ldap Core [![Build Status](https://travis-ci.org/Dreamscapes/Ldap-Core.svg)](https://travis-ci.org/Dreamscapes/Ldap-Core)
=========

> Object-oriented implementation of PHP's native ldap functions

## About

This small library provides access to ldap functions in a nice, object-oriented implementation. Additionally, you can use some mocking library to actually test your ldap interactions.

## Installation

### Requirements

 - PHP 5.4 and newer with LDAP support ([setup instructions](http://www.php.net/manual/en/ldap.installation.php))
 - OpenSSL module for SSL / TLS connections ([setup instructions](http://www.php.net/manual/en/openssl.installation.php))

> Support for `ldap_modify_batch` and `ldap_escape` are also available as long as they are present in your current PHP version.
 - `ldap_modify_batch` - available in PHP 5.4 branch since 5.4.26 and in higher versions of PHP since 5.5.10
 - `ldap_escape` -  available since PHP 5.6.0

### Via Composer

 `composer require dreamscapes/ldap-core:dev-master` (visit [Packagist](https://packagist.org/packages/Dreamscapes/ldap-core) for list of all available versions)

#### Installing on Travis-CI

Since Composer will not allow you to install a library on a system which does not meet the system requirements listed in *composer.json*, it is necessary that you enable the ldap extension **before** invoking `composer install`, i.e. in the *before_install* build lifecycle:

`echo "extension=ldap.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini`

See the [Travis docs](http://docs.travis-ci.com/user/languages/php/#Custom-PHP-configuration) for detailed information.

## Usage

There are two classes - `Dreamscapes\Ldap\Core\Ldap` and `Dreamscapes\Ldap\Core\ResultResource`, each of which implement part of the native ldap functions as instance methods (some functions which do not operate on the resource objects are static). The differentiating principle is simple - if the function deals with the state of the ldap connection, it is implemented in the first, whereas functions dealing with the data returned from ldap server (the result resource) are implemented in the latter class.

### Deviations

All methods are named as close to the original functions as possible, although with some improvements:

1. Ldap v3 protocol is used by default
1. The *ldap_* prefix is removed from all method names
1. The method names are **camelCased** instead of **underscore_based**
1. All methods return data by returning them and not by populating variables passed as references
1. Some functions are not implemented (usually because they are redundant)
1. Exceptions are thrown if the ldap protocol encounters an error, although standard PHP warnings and errors are not suppressed in most cases (still considering this)

### Example
```php
// Load Composer's autoload script...
include 'vendor/autoload.php';

// Import the class into current namespace
use Dreamscapes\Ldap\Core\Ldap;

// If ldap URI is provided, the Ldap instance will also open the connection
// via ldap_connect()
$con = new Ldap('ldap://example.com');
$con->bind('admin@example.com', 'my pass'); // Example AD credentials

// Read the rootDSE entry
$res = $con->read('', 'objectclass=*', ['*']);

// $res is now instance of ResultResource class
echo "Number of entries in resultset: " . $res->countEntries();
print_r($res->getEntries());
```

## Documentation

Online API documentation is available [here](http://dreamscapes.github.io/Ldap-Core). To generate API documentation offline:
```
git clone https://github.com/Dreamscapes/Ldap-Core.git
cd Ldap-Core
composer install
php vendor/bin/phpdoc
```
Documentation is now available at *./docs/index.html*

## License

This software is licensed under the **BSD (3-Clause) License**.
See the [LICENSE](LICENSE) file for more information.
