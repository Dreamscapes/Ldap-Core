# Ldap Core

[![Build Status][travis-badge]][travis-url]
![Built with GNU Make][make-badge]

> Object-oriented implementation of PHP's native ldap functions

## About

This small library provides access to ldap functions in a nice, object-oriented implementation.

Its purpose is not to provide *fancy extra* functionality for ldap interaction, but only to provide an object-level encapsulation around native PHP functions in a way that makes sense for OOP world. As such, this library is intended either for those who prefer OOP programming style, for those who want to be able to **test** their ldap interaction or for those who would like to write their own libraries which provide that *fancy extra* functionality.

## Installation

### Requirements

 - PHP 5.4 and newer with LDAP support ([setup instructions](http://www.php.net/manual/en/ldap.installation.php))
 - OpenSSL module for SSL / TLS connections ([setup instructions](http://www.php.net/manual/en/openssl.installation.php))

> Support for `ldap_modify_batch` and `ldap_escape` are also available as long as they are present in your current PHP version.
 - `ldap_modify_batch` - available in PHP 5.4 branch since 5.4.26 and in higher versions of PHP since 5.5.10
 - `ldap_escape` -  available since PHP 5.6.0

### Via Composer

 `composer require dreamscapes/ldap-core` (visit [Packagist](https://packagist.org/packages/Dreamscapes/ldap-core) for list of all available versions)

## Usage

There are two classes - `Dreamscapes\Ldap\Core\Ldap` and `Dreamscapes\Ldap\Core\Result`, each of which implement part of the native ldap functions as instance methods (some functions which do not operate on the resource objects are static). The differentiating principle is simple - if the function deals with the state of the ldap connection, it is implemented in the first, whereas functions dealing with the data returned from ldap server (the result resource) are implemented in the latter class.

### Deviations

All methods are named as close to the original functions as possible, although with some improvements:

1. Ldap v3 protocol is used by default
1. The *ldap_* prefix is removed from all method names
1. The method names are **camelCased** instead of **underscore_based**
1. All methods return data by returning them and not by populating variables passed as references
1. Some functions are not implemented (usually because they are redundant)
1. Exceptions are thrown if the ldap protocol encounters an error, and standard PHP warnings and errors are suppressed in most cases

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

// $res is now instance of Result class
echo "Number of entries in resultset: " . $res->countEntries();
print_r($res->getEntries());
```

## Documentation

Online API documentation is available [here](http://dreamscapes.github.io/Ldap-Core). To generate API documentation offline:
```
git clone https://github.com/Dreamscapes/Ldap-Core.git
cd Ldap-Core
composer install
make docs
```
Documentation is now available at *./docs/index.html*

## License

This software is licensed under the **BSD (3-Clause) License**.
See the [LICENSE](LICENSE) file for more information.

[travis-badge]: https://travis-ci.org/Dreamscapes/Ldap-Core.svg
[travis-url]: https://travis-ci.org/Dreamscapes/Ldap-Core
[make-badge]: https://img.shields.io/badge/built%20with-GNU%20Make-brightgreen.svg
