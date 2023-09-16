# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Releases

### [0.1.3] - 2023-09-16

* Composer update
  * composer/semver (3.3.2 => 3.4.0)
  * doctrine/instantiator (1.4.1 => 2.0.0)
  * friendsofphp/php-cs-fixer (v3.13.1 => v3.26.1)
  * ixnode/bash-version-manager (0.1.3 => 0.1.8)
  * ixnode/php-checker (0.1.3 => 0.1.9)
  * ixnode/php-container (0.1.2 => 0.1.10)
  * ixnode/php-exception (0.1.11 => 0.1.21)
  * myclabs/deep-copy (1.11.0 => 1.11.1)
  * nikic/php-parser (v4.15.2 => v4.17.1)
  * pdepend/pdepend (2.12.1 => 2.14.0)
  * phpstan/phpstan (1.9.4 => 1.10.34)
  * phpunit/php-code-coverage (9.2.21 => 9.2.28)
  * phpunit/phpunit (9.5.27 => 9.6.12)
  * povils/phpmnd (v3.0.1 => v3.2.0)
  * rector/rector (0.15.1 => 0.15.25)
  * sebastian/diff (4.0.4 => 4.0.5)
  * sebastian/environment (5.1.4 => 5.1.5)
  * sebastian/global-state (5.0.5 => 5.0.6)
  * sebastian/recursion-context (4.0.4 => 4.0.5)
  * sebastian/type (3.2.0 => 3.2.1)
  * symfony/config (v6.2.0 => v6.3.2)
  * symfony/console (v6.2.2 => v6.3.4)
  * symfony/dependency-injection (v6.2.2 => v6.3.4)
  * symfony/deprecation-contracts (v3.2.0 => v3.3.0)
  * symfony/event-dispatcher (v6.2.2 => v6.3.2)
  * symfony/event-dispatcher-contracts (v3.2.0 => v3.3.0)
  * symfony/filesystem (v6.2.0 => v6.3.1)
  * symfony/finder (v6.2.0 => v6.3.3)
  * symfony/options-resolver (v6.2.0 => v6.3.0)
  * symfony/polyfill-ctype (v1.27.0 => v1.28.0)
  * symfony/polyfill-intl-grapheme (v1.27.0 => v1.28.0)
  * symfony/polyfill-intl-normalizer (v1.27.0 => v1.28.0)
  * symfony/polyfill-mbstring (v1.27.0 => v1.28.0)
  * symfony/polyfill-php80 (v1.27.0 => v1.28.0)
  * symfony/polyfill-php81 (v1.27.0 => v1.28.0)
  * symfony/process (v6.2.0 => v6.3.4)
  * symfony/service-contracts (v3.2.0 => v3.3.0)
  * symfony/stopwatch (v6.2.0 => v6.3.0)
  * symfony/string (v6.2.2 => v6.3.2)
  * symfony/var-exporter (v6.2.2 => v6.3.4)
* Add more output to ValidatorDebugger
* Add last errors to Validator

### [0.1.2] - 2022-12-31

* Fix root directory to data path

### [0.1.1] - 2022-12-31

* Add root directory to data path

### [0.1.0] - 2022-12-31

* Initial release
* Add src
* Add tests
  * PHP Coding Standards Fixer
  * PHPMND - PHP Magic Number Detector
  * PHPStan - PHP Static Analysis Tool
  * PHPUnit - The PHP Testing Framework
  * Rector - Instant Upgrades and Automated Refactoring
* Add README.md
* Add LICENSE.md

## Add new version

```bash
# Checkout master branch
$ git checkout main && git pull

# Check current version
$ vendor/bin/version-manager --current

# Increase patch version
$ vendor/bin/version-manager --patch

# Change changelog
$ vi CHANGELOG.md

# Push new version
$ git add CHANGELOG.md VERSION && git commit -m "Add version $(cat VERSION)" && git push

# Tag and push new version
$ git tag -a "$(cat VERSION)" -m "Version $(cat VERSION)" && git push origin "$(cat VERSION)"
```
