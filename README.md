# PHP Json Schema Validator

[![Release](https://img.shields.io/github/v/release/ixnode/php-json-schema-validator)](https://github.com/ixnode/php-json-schema-validator/releases)
[![](https://img.shields.io/github/release-date/ixnode/php-json-schema-validator)](https://github.com/ixnode/php-json-schema-validator/releases)
![](https://img.shields.io/github/repo-size/ixnode/php-json-schema-validator.svg)
[![PHP](https://img.shields.io/badge/PHP-^8.2-777bb3.svg?logo=php&logoColor=white&labelColor=555555&style=flat)](https://www.php.net/supported-versions.php)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg?style=flat)](https://phpstan.org/user-guide/rule-levels)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-Unit%20Tests-6b9bd2.svg?style=flat)](https://phpunit.de)
[![PHPCS](https://img.shields.io/badge/PHPCS-PSR12-brightgreen.svg?style=flat)](https://www.php-fig.org/psr/psr-12/)
[![PHPMD](https://img.shields.io/badge/PHPMD-ALL-364a83.svg?style=flat)](https://github.com/phpmd/phpmd)
[![Rector - Instant Upgrades and Automated Refactoring](https://img.shields.io/badge/Rector-PHP%208.2-73a165.svg?style=flat)](https://github.com/rectorphp/rector)
[![LICENSE](https://img.shields.io/github/license/ixnode/php-json-schema-validator)](https://github.com/ixnode/php-json-schema-validator/blob/master/LICENSE)

> An easy-to-use PHP Json Schema Validator on top of [opis/json-schema](https://github.com/opis/json-schema).

## 1. Usage

```php
use Ixnode\PhpJsonSchemaValidator\Validator;
```

```php
$data = '[1, 2, 3]';

$schema = [
    'type' => 'object'
];

$validator = new Validator(new Json($data), new Json($schema));

$valid = $validator->validate();
// (bool) true
```

## 2. Installation

```bash
composer require ixnode/php-json-schema-validator
```

```bash
vendor/bin/php-json-schema-validator -V
```

```bash
php-json-schema-validator 0.1.0 (12-31-2022 15:51:08) - Björn Hempel <bjoern@hempel.li>
```

## 3. Library development

```bash
git clone git@github.com:ixnode/php-json-schema-validator.git && cd php-json-schema-validator
```

```bash
composer install
```

```bash
composer test
```

## 4. License

This tool is licensed under the MIT License - see the [LICENSE](/LICENSE) file for details