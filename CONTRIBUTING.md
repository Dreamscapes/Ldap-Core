## Coding standards

This library follows [PSR-2](http://www.php-fig.org/psr/psr-2) coding style. When sending pull requests, always make sure your code follows this standard.

To check your code, run:
```
composer install
./vendor/bin/phpcs --standard=PSR2 --ignore=vendor/*,docs/* -p .
```
