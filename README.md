# PHP Json Schema Validator

[![Release](https://img.shields.io/github/v/release/ixnode/php-json-schema-validator)](https://github.com/ixnode/php-json-schema-validator/releases)
[![PHP](https://img.shields.io/badge/PHP-^8.0-777bb3.svg?logo=php&logoColor=white&labelColor=555555&style=flat)](https://www.php.net/supported-versions.php)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg?style=flat)](https://phpstan.org/user-guide/rule-levels)
[![PHPCS](https://img.shields.io/badge/PHPCS-PSR12-brightgreen.svg?style=flat)](https://www.php-fig.org/psr/psr-12/)
[![LICENSE](https://img.shields.io/github/license/ixnode/php-json-schema-validator)](https://github.com/ixnode/php-json-schema-validator/blob/master/LICENSE)

> An easy-to-use PHP Json Schema Validator on top of [opis/json-schema](https://github.com/opis/json-schema).

## Installation

```bash
composer require ixnode/php-json-schema-validator
```

```bash
vendor/bin/php-json-schema-validator -V
```

```bash
php-json-schema-validator 0.1.0 (12-31-2022 15:51:08) - Björn Hempel <bjoern@hempel.li>
```

## Usage

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
```

## Development

```bash
git clone git@github.com:ixnode/php-json-schema-validator.git && cd php-json-schema-validator
```

```bash
composer install
```

```bash
composer test
```

## License

This tool is licensed under the MIT License - see the [LICENSE](/LICENSE) file for details