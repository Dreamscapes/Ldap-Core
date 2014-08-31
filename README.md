Ldap Core [![Build Status](https://travis-ci.org/Dreamscapes/Ldap-Core.svg)](https://travis-ci.org/Dreamscapes/Ldap-Core)
=========

> Object-oriented implementation of PHP's native ldap functions

## About

This small library provides access to ldap functions in a nice, object-oriented implementation. In addition, several interfaces are provided which will make testing your code much more simple.

## Installation

### Requirements

 - PHP 5.4.0 and newer with LDAP support ( [setup instructions](http://www.php.net/manual/en/ldap.installation.php) )
 - OpenSSL module for SSL / TLS connections ( [setup instructions](http://www.php.net/manual/en/openssl.installation.php) )

### Via Composer

 `composer require dreamscapes/ldap-core:dev-master` ( visit [Packagist](https://packagist.org/packages/Dreamscapes/ldap-core) for list of all available versions )

## Usage

There are two classes - `LinkResource` and `ResultResource`, each of which implement part of the native ldap functions as class methods. The differentiating principle is simple - if the function deals with the state of the ldap connection, it is implemented in the first, whereas functions dealing with the data returned from ldap server (the result resource) are implemented in the latter.

### Deviations

All methods are named as close to the original functions as possible, although with some improvements:

1. Ldap v3 protocol is used by default
1. The *ldap_* prefix is removed from all method names
1. The method names are **camelCased** instead of **underscore_based**
1. All methods return data by returning them and not by populating variables passed as function arguments
1. Some functions are not implemented ( usually because they are redundant )
1. Exceptions are thrown if the ldap protocol encounters an error, although standard PHP warnings and errors are not suppressed ( still considering this )

### Example
```php
// Load Composer's autoload script...
include 'vendor/autoload.php';

// Import the class into current namespace
use Dreamscapes\Ldap\Core\LinkResource;

// If ldap URI is provided, the LinkResource will also open the connection
// via ldap_connect()
$con = new LinkResource('ldap://example.com');
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
composer install
./vendor/bin/phpdoc.php
```
Documentation is now available at *./docs/index.html*

## License

This software is licensed under the **BSD (3-Clause) License**.
See the [LICENSE](LICENSE) file for more information.
