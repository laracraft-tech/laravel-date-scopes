<?php

namespace LaracraftTech\LaravelDateScopes;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder ofLastUnit(string $dateUnit, int $value, DateRange $customRange = null)
 *
 * @method static Builder ofJustNow()
 * @method static Builder ofLastSecond()
 * @method static Builder ofLast15Seconds(DateRange $customRange = null)
 * @method static Builder ofLast30Seconds(DateRange $customRange = null)
 * @method static Builder ofLast45Seconds(DateRange $customRange = null)
 * @method static Builder ofLast60Seconds(DateRange $customRange = null)
 * @method static Builder ofLastSeconds(int $seconds, DateRange $customRange = null)
 *
 * @method static Builder ofLastMinute()
 * @method static Builder ofLast15Minutes(DateRange $customRange = null)
 * @method static Builder ofLast30Minutes(DateRange $customRange = null)
 * @method static Builder ofLast45Minutes(DateRange $customRange = null)
 * @method static Builder ofLast60Minutes(DateRange $customRange = null)
 * @method static Builder ofLastMinutes(int $minutes, DateRange $customRange = null)
 *
 * @method static Builder ofLastHour()
 * @method static Builder ofLast6Hours(DateRange $customRange = null)
 * @method static Builder ofLast12Hours(DateRange $customRange = null)
 * @method static Builder ofLast18Hours(DateRange $customRange = null)
 * @method static Builder ofLast24Hours(DateRange $customRange = null)
 * @method static Builder ofLastHours(int $hours, DateRange $customRange = null)
 *
 * @method static Builder ofToday()
 * @method static Builder ofYesterday()
 * @method static Builder ofLast7Days(DateRange $customRange = null)
 * @method static Builder ofLast14Days(DateRange $customRange = null)
 * @method static Builder ofLast21Days(DateRange $customRange = null)
 * @method static Builder ofLast30Days(DateRange $customRange = null)
 * @method static Builder ofLastDays(int $days, DateRange $customRange = null)
 *
 * @method static Builder ofLastWeek()
 * @method static Builder ofLast2Weeks(DateRange $customRange = null)
 * @method static Builder ofLast3Weeks(DateRange $customRange = null)
 * @method static Builder ofLast4Weeks(DateRange $customRange = null)
 * @method static Builder ofLastWeeks(int $weeks, DateRange $customRange = null)
 *
 * @method static Builder ofLastMonth()
 * @method static Builder ofLast3Months(DateRange $customRange = null)
 * @method static Builder ofLast6Months(DateRange $customRange = null)
 * @method static Builder ofLast9Months(DateRange $customRange = null)
 * @method static Builder ofLast12Months(DateRange $customRange = null)
 * @method static Builder ofLastMonths(int $months, DateRange $customRange = null)
 *
 * @method static Builder ofLastQuarter()
 * @method static Builder ofLast2Quarters(DateRange $customRange = null)
 * @method static Builder ofLast3Quarters(DateRange $customRange = null)
 * @method static Builder ofLast4Quarters(DateRange $customRange = null)
 * @method static Builder ofLastQuarters(int $quarters, DateRange $customRange = null)
 *
 * @method static Builder ofLastYear()
 * @method static Builder ofLastYears(int $years, DateRange $customRange = null)
 *
 * @method static Builder ofLastDecade()
 * @method static Builder ofLastDecades(int $decades, DateRange $customRange = null)
 *
 * @method static Builder ofLastCentury()
 * @method static Builder ofLastCenturies(int $decades, DateRange $customRange = null)
 *
 * @method static Builder ofLastMillennium()
 * @method static Builder ofLastMillenniums(int $millennium, DateRange $customRange = null)
 *
 * @method static Builder secondToNow()
 * @method static Builder minuteToNow()
 * @method static Builder hourToNow()
 * @method static Builder dayToNow()
 * @method static Builder weekToDate()
 * @method static Builder monthToDate()
 * @method static Builder quarterToDate()
 * @method static Builder yearToDate()
 * @method static Builder decadeToDate()
 * @method static Builder centuryToDate()
 * @method static Builder millenniumToDate()
 */
trait DateScopes
{
    /**
     * For example, if you subtract one month from March 31, you would get February 31, which is not a valid date.
     * The subMonthsNoOverflow method for instance would instead return February 28 (or February 29 in a leap year),
     * as it adjusts the day to the last day of the month when the resulting date would be invalid.
     *
     * In Carbon, the date units that can have this "overflow" behavior are months, years, decades, etc. because their lengths can vary.
     * Days, hours, minutes, and seconds have fixed lengths, so they do not have this issue.
     *
     * @var array|string[]
     */
    private array $fixedLengthDateUnits = [
        'second',
        'minute',
        'hour',
        'day',
        'week'
    ];

    /**
     * @param Builder $query Eloquent Builder
     * @param string $dateUnit A valid date unit, such as hour, day, month, year etc...
     * @param int $value How many hours, days etc. you want to get.
     * @param null $startFrom Any parseable date to start from
     * @param DateRange|null $customRange
     * @return Builder
     */
    public function scopeOfLastUnit(
        Builder $query,
        string $dateUnit,
        int $value,
        $startFrom = null,
        DateRange $customRange = null
    ): Builder {
        if ($value <= 0) {
            throw DateException::invalidValue();
        }

        $dateRange = $customRange ?? DateRange::from(config('date-scopes.default_range'));

        $startFunc = 'startOf'.ucfirst($dateUnit);
        $endFunc = 'endOf'.ucfirst($dateUnit);

        $applyNoOverflow = (! in_array($dateUnit, $this->fixedLengthDateUnits)) ? 'NoOverflow' : '' ;
        $subFunc = 'sub'.ucfirst($dateUnit).'s'.$applyNoOverflow;
        $sub = 1;

        $startFrom = CarbonImmutable::parse($startFrom ?? now());

        if ($dateRange === DateRange::EXCLUSIVE) {
            $range = [
                'from' => $startFrom->$subFunc($value)->$startFunc(),
                'to' => $startFrom->$subFunc($sub)->$endFunc(),
            ];
        } else {
            $range = [
                'from' => $startFrom->$subFunc($value - $sub)->$startFunc(),
                'to' => $startFrom->$endFunc(),
            ];
        }

//        if (defined('DATE_SCOPE_DEBUG'))
//            dd(collect($range)->transform(fn ($item) => $item->format('Y-m-d H:i:s'))->toArray());

        $createdColumnName = (self::CREATED_AT != 'created_at') ? self::CREATED_AT : config('date-scopes.created_column');

        return $query->whereBetween($createdColumnName, $range);
    }

    // START SECONDS
    public function scopeOfJustNow(Builder $query, $startFrom = null): Builder {return $query->ofLastSeconds(1, $startFrom, DateRange::INCLUSIVE);}
    public function scopeOfLastSecond(Builder $query, $startFrom = null): Builder {return $query->ofLastSeconds(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast15Seconds(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastSeconds(15, $startFrom, $customRange);}
    public function scopeOfLast30Seconds(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastSeconds(30, $startFrom, $customRange);}
    public function scopeOfLast45Seconds(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastSeconds(45, $startFrom, $customRange);}
    public function scopeOfLast60Seconds(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastSeconds(60, $startFrom, $customRange);}

    public function scopeOfLastSeconds(Builder $query, int $seconds, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('second', $seconds, $startFrom, $customRange);
    }

    // START MINUTES
    public function scopeOfLastMinute(Builder $query, $startFrom = null): Builder {return $query->ofLastMinutes(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast15Minutes(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMinutes(15, $startFrom, $customRange);}
    public function scopeOfLast30Minutes(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMinutes(30, $startFrom, $customRange);}
    public function scopeOfLast45Minutes(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMinutes(45, $startFrom, $customRange);}
    public function scopeOfLast60Minutes(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMinutes(60, $startFrom, $customRange);}

    public function scopeOfLastMinutes(Builder $query, int $minutes, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('minute', $minutes, $startFrom, $customRange);
    }

    // START HOURS
    public function scopeOfLastHour(Builder $query, $startFrom = null): Builder {return $query->ofLastHours(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast6Hours(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastHours(6, $startFrom, $customRange);}
    public function scopeOfLast12Hours(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastHours(12, $startFrom, $customRange);}
    public function scopeOfLast18Hours(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastHours(18, $startFrom, $customRange);}
    public function scopeOfLast24Hours(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastHours(24, $startFrom, $customRange);}

    public function scopeOfLastHours(Builder $query, int $hours, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('hour', $hours, $startFrom, $customRange);
    }

    // START DAYS
    public function scopeOfToday(Builder $query, $startFrom = null): Builder {return $query->ofLastDays(1, $startFrom, DateRange::INCLUSIVE);}
    public function scopeOfYesterday(Builder $query, $startFrom = null): Builder {return $query->ofLastDays(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast7Days(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastDays(7, $startFrom, $customRange);}
    public function scopeOfLast14Days(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastDays(14, $startFrom, $customRange);}
    public function scopeOfLast21Days(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastDays(21, $startFrom, $customRange);}
    public function scopeOfLast30Days(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastDays(30, $startFrom, $customRange);}

    public function scopeOfLastDays(Builder $query, int $days, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('day', $days, $startFrom, $customRange);
    }

    // START WEEKS
    public function scopeOfLastWeek(Builder $query, $startFrom = null): Builder {return $query->ofLastWeeks(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast2Weeks(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastWeeks(2, $startFrom, $customRange);}
    public function scopeOfLast3Weeks(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastWeeks(3, $startFrom, $customRange);}
    public function scopeOfLast4Weeks(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastWeeks(4, $startFrom, $customRange);}

    public function scopeOfLastWeeks(Builder $query, int $weeks, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('week', $weeks, $startFrom, $customRange);
    }

    // START MONTHS
    public function scopeOfLastMonth(Builder $query, $startFrom = null): Builder {return $query->ofLastMonths(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast3Months(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMonths(3, $startFrom, $customRange);}
    public function scopeOfLast6Months(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMonths(6, $startFrom, $customRange);}
    public function scopeOfLast9Months(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMonths(9, $startFrom, $customRange);}
    public function scopeOfLast12Months(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastMonths(12, $startFrom, $customRange);}

    public function scopeOfLastMonths(Builder $query, int $months, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('month', $months, $startFrom, $customRange);
    }

    // START QUARTER
    public function scopeOfLastQuarter(Builder $query, $startFrom = null): Builder {return $query->ofLastQuarters(1, $startFrom, DateRange::EXCLUSIVE);}
    public function scopeOfLast2Quarters(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastQuarters(2, $startFrom, $customRange);}
    public function scopeOfLast3Quarters(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastQuarters(3, $startFrom, $customRange);}
    public function scopeOfLast4Quarters(Builder $query, $startFrom = null, DateRange $customRange = null): Builder {return $query->ofLastQuarters(4, $startFrom, $customRange);}

    public function scopeOfLastQuarters(Builder $query, int $quarters, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('quarter', $quarters, $startFrom, $customRange);
    }

    // START YEARS
    public function scopeOfLastYear(Builder $query, $startFrom = null): Builder {return $query->ofLastYears(1, $startFrom, DateRange::EXCLUSIVE);}

    public function scopeOfLastYears(Builder $query, int $years, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('year', $years, $startFrom, $customRange);
    }

    // START DECADE
    public function scopeOfLastDecade(Builder $query, $startFrom = null): Builder {return $query->ofLastDecades(1, $startFrom, DateRange::EXCLUSIVE);}

    public function scopeOfLastDecades(Builder $query, int $decades, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('decade', $decades, $startFrom, $customRange);
    }

    // START CENTURIES
    public function scopeOfLastCentury(Builder $query, $startFrom = null): Builder {return $query->ofLastCenturies(1, $startFrom, DateRange::EXCLUSIVE);}

    public function scopeOfLastCenturies(Builder $query, int $centuries, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('century', $centuries, $startFrom, $customRange);
    }

    // START MILLENNIUM
    public function scopeOfLastMillennium(Builder $query, $startFrom = null): Builder {return $query->ofLastMillenniums(1, $startFrom, DateRange::EXCLUSIVE);}

    public function scopeOfLastMillenniums(Builder $query, int $millennium, $startFrom = null, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('millennium', $millennium, $startFrom, $customRange);
    }

    // START CURRENT TO NOW

    public function scopeSecondToNow(Builder $query): Builder {return $query->ofLastSeconds(1, customRange: DateRange::INCLUSIVE);}
    public function scopeMinuteToNow(Builder $query): Builder {return $query->ofLastMinutes(1, customRange: DateRange::INCLUSIVE);}
    public function scopeHourToNow(Builder $query): Builder {return $query->ofLastHours(1, customRange: DateRange::INCLUSIVE);}
    public function scopeDayToNow(Builder $query): Builder {return $query->ofLastDays(1, customRange: DateRange::INCLUSIVE);}
    public function scopeWeekToDate(Builder $query): Builder {return $query->ofLastWeeks(1, customRange: DateRange::INCLUSIVE);}
    public function scopeMonthToDate(Builder $query): Builder {return $query->ofLastMonths(1, customRange: DateRange::INCLUSIVE);}
    public function scopeQuarterToDate(Builder $query): Builder {return $query->ofLastQuarters(1, customRange: DateRange::INCLUSIVE);}
    public function scopeYearToDate(Builder $query): Builder {return $query->ofLastYears(1, customRange: DateRange::INCLUSIVE);}
    public function scopeDecadeToDate(Builder $query): Builder {return $query->ofLastDecades(1, customRange: DateRange::INCLUSIVE);}
    public function scopeCenturyToDate(Builder $query): Builder {return $query->ofLastCenturies(1, customRange: DateRange::INCLUSIVE);}
    public function scopeMillenniumToDate(Builder $query): Builder {return $query->ofLastMillenniums(1, customRange: DateRange::INCLUSIVE);}
}

