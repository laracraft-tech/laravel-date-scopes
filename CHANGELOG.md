# Changelog

All notable changes to `laravel-date-scopes` will be documented in this file.

## v2.3.0 - 2025-02-23

### What's Changed

* Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/laracraft-tech/laravel-date-scopes/pull/22
* Laravel 12.x Compatibility by @laravel-shift in https://github.com/laracraft-tech/laravel-date-scopes/pull/23

### New Contributors

* @laravel-shift made their first contribution in https://github.com/laracraft-tech/laravel-date-scopes/pull/23

**Full Changelog**: https://github.com/laracraft-tech/laravel-date-scopes/compare/v2.2.1...v2.3.0

## v2.2.1 - 2025-01-17

### What's Changed

* Bump dependabot/fetch-metadata from 2.1.0 to 2.2.0 by @dependabot in https://github.com/laracraft-tech/laravel-date-scopes/pull/20
* Fix some PHP 8.4 deprecations by @ziming in https://github.com/laracraft-tech/laravel-date-scopes/pull/21

### New Contributors

* @ziming made their first contribution in https://github.com/laracraft-tech/laravel-date-scopes/pull/21

**Full Changelog**: https://github.com/laracraft-tech/laravel-date-scopes/compare/v2.2.0...v2.2.1

## v2.2.0 - 2024-07-02

### What's changed

* Carbon 3 support by @keatliang2005

## v2.1.0 - 2024-03-07

* Laravel 11 Support

## v2.0.1 - 2023-10-12

### What's changed

- fixed php doc block

## v2.0.0 - 2023-10-12

### What's Changed

- you can now pass `startFrom` as a parameter to query between certain ranges. For instance: `Transaction::ofLastYear(startFrom: '2020-01-01')`. You may need to change the order of your arguments or use named arguments. Checkout [UPGRADING](https://github.com/laracraft-tech/laravel-date-scopes/blob/main/UPGRADING.md) file for that.

## v1.1.1 - 2023-05-02

### What's Changed

- added tests for custom created_at column name

## v1.1.0 - 2023-05-02

### What's Changed

- support for custom created_at column names per model #5

## v1.0.6 - 2023-04-17

### Added

- method doc blocks for IDE auto-completion

## v1.0.5 - 2023-04-15

### Added

- test suite
- century scope

## v1.0.4 - 2023-04-15

### Fixed

- fixed overflow date units
- fixed singular scopes
- fixed second date unit

### Added

- added justNow scope

## v1.0.3 - 2023-04-14

### Fixed

- Fixed date overflows

## v1.0.2 - 2023-04-08

### Bugs

- fixed debug

## v1.0.1 - 2023-04-08

### Bugs

- fixed dependencies

## v1.0.0 - 2023-04-08

### Version 1

- lots of date scopes!
