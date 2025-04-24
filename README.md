# Laravel Date Scopes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)
[![Tests](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/run-tests.yml)
[![License](https://img.shields.io/packagist/l/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)
[![Total Downloads](https://img.shields.io/packagist/dt/laracraft-tech/laravel-date-scopes.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-date-scopes)
[![Imports](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/check_imports.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-date-scopes/actions/workflows/check_imports.yml)

This package provides a big range of useful **date scopes** for your Laravel Eloquent models!

Let's assume you have a `Transaction` model.
If you now give it the `DateScopes` trait, you can do something like this:

```php
use LaracraftTech\LaravelDateScopes\DateScopes;

class Transaction extends Model
{
    use DateScopes;
}

// query transactions created today
Transaction::ofToday();
 // query transactions created during the last week
Transaction::ofLastWeek();
 // query transactions created during the start of the current month till now
Transaction::monthToDate();
 // query transactions created during the last year, start from 2020
Transaction::ofLastYear(startFrom: '2020-01-01');

// ... and much more scopes are available (see below)

// For sure, you can chain any Builder function you want here.
// Such as these aggregations, for instance:
Transaction::ofToday()->sum('amount');
Transaction::ofLastWeek()->avg('amount');
```

## ToC

- [`Installation`](#installation)
- [`Configuration`](#configuration)
  - [`Global configuration`](#global-configuration)
  - [`Fluent date range configuration`](#fluent-date-range-configuration)
  - [`Fluent created_at column configuration`](#fluent-date-range-configuration)
  - [`Custom start date`](#custom-start-date)
- [`Scopes`](#scopes)

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

### Global configuration

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
     * Note that you can also fluently specify the range for quite every scope we offer
     * directly when using the scope:
     * Transaction::ofLast7Days(customRange: DateRange::INCLUSIVE); (this works for all but the singular "ofLast"-scopes)
     * This will do an inclusive query, even though the global default range here is set to exclusive.
     */
    'default_range' => env('DATE_SCOPES_DEFAULT_RANGE', DateRange::EXCLUSIVE->value),

    /**
     * If you use a global custom created_at column name, change it here.
     */
    'created_column' => env('DATE_SCOPES_CREATED_COLUMN', 'created_at'),
];
```

If you want to change the default range to inclusive set `DATE_SCOPES_DEFAULT_RANGE=inclusive` in your `.env`.

### Fluent date range configuration

As already mentioned above in the `default_range` config description text,
you can also fluently specify the range for quite every scope we offer
directly when using the scope:

```php
// This works for all "ofLast"-scopes, expect the singulars like "ofLastHour",
// because it would not make sense for those.
Transaction::ofLast7Days(customRange: DateRange::INCLUSIVE);
```

This will do an inclusive query (today-6 days), even though the global default range here was set to exclusive.

### Fluent created_at column configuration

If you only want to change the ```created_at``` field in one of your models and not globally just do:

```php
use LaracraftTech\LaravelDateScopes\DateScopes;

class Transaction extends Model
{
    use DateScopes;
    
    public $timestamps = false;

    const CREATED_AT = 'custom_created_at';
}
// also make sure to omit the default $table->timestamps() function in your migration
// and use something like this instead: $table->timestamp('custom_created_at')->nullable();
```

### Custom start date

If you want data not starting from now, but from another date, you can do this with:

```php
// query transactions created during 2019-2020
Transaction::ofLastYear(startFrom: '2020-01-01')
```

### Custom datetime column

If you want to use column other than `created_at` column, you can pass the column name as parameter to the scope:


```php
Transaction::ofToday(column: 'approved_at')
```

## Scopes

- [`seconds`](#seconds)
- [`minutes`](#minutes)
- [`hours`](#hours)
- [`days`](#days)
- [`weeks`](#weeks)
- [`months`](#months)
- [`quarters`](#quarters)
- [`years`](#years)
- [`decades`](#decades)
- [`centuries`](#centuries)
- [`millenniums`](#millenniums)
- [`toNow/toDate`](#toNowtoDate)

### Seconds

```php
// query by SECONDS
Transaction::ofJustNow(); // query transactions created just now
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

### Quarters

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

### Centuries

The **centuries** may return a different range then you maybe would expect. For instance `Transaction::ofLastCentury()` would apply a range from 1901-01-01 00:00:00 to 2000-12-31 23:59:59.
Maybe you would expect a range from: 1900-01-01 00:00:00 to 1999-12-31 23:59:59.

Checkout Wikipedia for this behavior: https://en.wikipedia.org/wiki/20th_century


```php
// query by CENTURIES
Transaction::ofLastCentury(); // query transactions created during the last century
Transaction::ofLastCenturies(2); // query transactions created during the last N centuries
```

### Millenniums

The **millenniums** may return a different range then you maybe would expect. For instance `Transaction::ofLastMillennium()` would apply a range from 1001-01-01 00:00:00 to 2000-12-31 23:59:59.
Maybe you would expect a range from: 1000-01-01 00:00:00 to 1999-12-31 23:59:59.

Checkout Wikipedia for this behavior: https://en.wikipedia.org/wiki/2nd_millennium

```php
// query by MILLENNIUMS
Transaction::ofLastMillennium(); // query transactions created during the last millennium
Transaction::ofLastMillenniums(2); // query transactions created during the last N millenniums
```

### toNow/toDate

```php
// query by toNow/toDate
Transaction::secondToNow(); // query transactions created during the start of the current second till now (equivalent of just now)
Transaction::minuteToNow(); // query transactions created during the start of the current minute till now
Transaction::hourToNow(); // query transactions created during the start of the current hour till now
Transaction::dayToNow(); // query transactions created during the start of the current day till now
Transaction::weekToDate(); // query transactions created during the start of the current week till now
Transaction::monthToDate(); // query transactions created during the start of the current month till now
Transaction::quarterToDate(); // query transactions created during the start of the current quarter till now
Transaction::yearToDate(); // query transactions created during the start of the current year till now
Transaction::decadeToDate(); // query transactions created during the start of the current decade till now
Transaction::centuryToDate(); // query transactions created during the start of the current century till now
Transaction::millenniumToDate(); // query transactions created during the start of the current millennium till now
```

## Testing

```bash
composer test
```

## Upgrading

Please see [UPGRADING](UPGRADING.md) for details.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Zacharias Creutznacher](https://github.com/laracraft-tech)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
