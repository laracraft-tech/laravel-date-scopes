# Laravel Date Scopes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)
[![Tests](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/run-tests.yml)
[![License](https://img.shields.io/packagist/l/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)
<!--[![Total Downloads](https://img.shields.io/packagist/dt/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)-->

The package provides a big range of useful date scopes for your Laravel Eloquent models!

## Installation

You can install the package via composer:

```bash
composer require laracraft-tech/laravel-date-scopes
```

## Configuration

### Inclusive/Exclusive

In **statistics**, when asking for "the last 7 days", the current day may or may not be included
in the calculation depending on the context and the specific requirements of the analysis.

If you want to **include** the current day in the calculation, you would generally use an **inclusive** range,
meaning that you would include records created on the **current day** as well as records
created in the previous 6 days.

If you want to **exclude** the current day in the calculation, you would generally use an **exclusive** range,
meaning that you would include records created in the previous 7 days,
but not records created on the **current day**.

Ultimately, it **depends** on the context and what you're trying to achieve with your data.
It's always a good idea to clarify the requirements and expectations with stakeholders
to ensure that you're including or excluding the correct records.

The same **concept** applies to other time intervals like weeks, months, quarters, and years etc.

The default for this package is **exclusive** approach,
which means when you for instance query for the last 7 days it will **not include** the current day!
You can change the default if you need in the published config file.

### Config

You can publish the config file with:

```bash
php artisan vendor:publish --tag="date-scopes-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * If you want to include the current day/week/month/year etc. in the range,
     * you could use the inclusive range here as a default.
     * Note that you can also optionally specify it for quite every scope we offer
     * directly when using the scope:
     * Transaction::ofLast7Days(DateRange::INCLUSIVE); (this works for all but the singular "ofLast"-scopes)
     * This will do an inclusive query, even though the global default range here is set to exclusive.
     */
    'default_range' => env('DATE_SCOPES_DEFAULT_RANGE', DateRange::EXCLUSIVE->value),

    /**
     * If you have a custom created_at column name, change it here.
     */
    'created_column' => env('DATE_SCOPES_CREATED_COLUMN', 'created_at'),
];
```

If you want to change the default range to inclusive set `DATE_SCOPES_DEFAULT_RANGE=inclusive` in your `.env`.

## Scopes

- [`seconds`](#seconds)
- [`minutes`](#minutes)
- [`hours`](#hours)
- [`months`](#months)
- [`quarters`](#quarters)
- [`years`](#years)
- [`decades`](#decades)
- [`millenniums`](#millenniums)
- [`toNow/toDate`](#toNowtoDate)
 
Let's assume you have an `Transaction` model class.
Now when you give it the `DateScopes` trait, you can use the following scopes:

```php
use LaracraftTech\LaravelDateScopes\DateScopes;

class Transaction extends Model
{
    use DateScopes;
}
```

### Seconds

```php
// query by SECONDS
Transaction::ofLastSecond(); // query transactions created during the last second
Transaction::ofLast15Seconds(); // query transactions created during the last 15 seconds
Transaction::ofLast30Seconds(); // query transactions created during the last 30 seconds
Transaction::ofLast45Seconds(); // query transactions created during the last 45 seconds
Transaction::ofLast60Seconds(); // query transactions created during the last 60 seconds
Transaction::ofLastSeconds(120); // query transactions created during the last N seconds
```

### Minutes

```php
// query by MINUTES
Transaction::ofLastMinute(); // query transactions created during the last minute
Transaction::ofLast15Minutes(); // query transactions created during the last 15 minutes
Transaction::ofLast30Minutes(); // query transactions created during the last 30 minutes
Transaction::ofLast45Minutes(); // query transactions created during the last 45 minutes
Transaction::ofLast60Minutes(); // query transactions created during the last 60 minutes
Transaction::ofLastMinutes(120); // query transactions created during the last N minutes
```

### Hours

```php
// query by HOURS
Transaction::ofLastHour(); // query transactions created during the last hour
Transaction::ofLast6Hours(); // query transactions created during the last 6 hours
Transaction::ofLast12Hours(); // query transactions created during the last 12 hours
Transaction::ofLast18Hours(); // query transactions created during the last 18 hours
Transaction::ofLast24Hours(); // query transactions created during the last 24 hours
Transaction::ofLastHours(48); // query transactions created during the last N hours
```

### Days

```php
// query by DAYS
Transaction::ofToday(); // query transactions created today
Transaction::ofYesterday(); // query transactions created yesterday
Transaction::ofLast7Days(); // query transactions created during the last 7 days
Transaction::ofLast21Days(); // query transactions created during the last 21 days
Transaction::ofLast30Days(); // query transactions created during the last 30 days
Transaction::ofLastDays(60); // query transactions created during the last N days
```

### Weeks

```php
// query by WEEKS
Transaction::ofLastWeek(); // query transactions created during the last week
Transaction::ofLast2Weeks(); // query transactions created during the last 2 weeks
Transaction::ofLast3Weeks(); // query transactions created during the last 3 weeks
Transaction::ofLast4Weeks(); // query transactions created during the last 4 weeks
Transaction::ofLastWeeks(8); // query transactions created during the last N weeks
```

### Months

```php
// query by MONTHS
Transaction::ofLastMonth(); // query transactions created during the last month
Transaction::ofLast3Months(); // query transactions created during the last 3 months
Transaction::ofLast6Months(); // query transactions created during the last 6 months
Transaction::ofLast9Months(); // query transactions created during the last 9 months
Transaction::ofLast12Months(); // query transactions created during the last 12 months
Transaction::ofLastMonths(24); // query transactions created during the last N months
```

### Quarter

```php
// query by QUARTERS
Transaction::ofLastQuarter(); // query transactions created during the last quarter
Transaction::ofLast2Quarters(); // query transactions created during the last 2 quarters
Transaction::ofLast3Quarters(); // query transactions created during the last 3 quarters
Transaction::ofLast4Quarters(); // query transactions created during the last 4 quarters
Transaction::ofLastQuarters(8); // query transactions created during the last N quarters
```

### Years

```php
// query by YEARS
Transaction::ofLastYear(); // query transactions created during the last year
Transaction::ofLastYears(2); // query transactions created during the last N years
```

### Decades

```php
// query by DECADES
Transaction::ofLastDecade(); // query transactions created during the last decade
Transaction::ofLastDecades(2); // query transactions created during the last N decades
```

### Millennium

```php
// query by MILLENNIUM
Transaction::ofLastMillennium(); // query transactions created during the last millennium
Transaction::ofLastMillenniums(2); // query transactions created during the last N millenniums
```

### toNow/toDate

```php
// query by toNow/toDate
Transaction::secondToNow(); // query transactions created during the start of the current second to now (not really usefull I guess)
Transaction::minuteToNow(); // query transactions created during the start of the current minute to now
Transaction::hourToNow(); // query transactions created during the start of the current hour to now
Transaction::dayToNow(); // query transactions created during the start of the current day to now
Transaction::weekToDate(); // query transactions created during the start of the current week to now
Transaction::monthToDate(); // query transactions created during the start of the current month to now
Transaction::quarterToDate(); // query transactions created during the start of the current quarter to now
Transaction::yearToDate(); // query transactions created during the start of the current year to now
Transaction::decadeToDate(); // query transactions created during the start of the current decade to now
Transaction::millenniumToDate(); // query transactions created during the start of the current millennium to now
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Zacharias Creutznacher](https://github.com/laracraft-tech)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
