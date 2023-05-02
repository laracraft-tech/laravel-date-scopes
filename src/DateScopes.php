<?php

namespace LaracraftTech\LaravelDateScopes;

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
     * @param  Builder  $query Eloquent Builder
     * @param  string  $dateUnit A valid date unit, such as hour, day, month, year etc...
     * @param  int  $value How many hours, days etc. you want to get.
     */
    public function scopeOfLastUnit(Builder $query, string $dateUnit, int $value, DateRange $customRange = null): Builder
    {
        if ($value <= 0) {
            throw DateException::invalidValue();
        }

        $dateRange = $customRange ?? DateRange::from(config('date-scopes.default_range'));

        $startFunc = 'startOf'.ucfirst($dateUnit);
        $endFunc = 'endOf'.ucfirst($dateUnit);

        $applyNoOverflow = (! in_array($dateUnit, $this->fixedLengthDateUnits)) ? 'NoOverflow' : '' ;
        $subFunc = 'sub'.ucfirst($dateUnit).'s'.$applyNoOverflow;
        $sub = 1;

        if ($dateRange === DateRange::EXCLUSIVE) {
            $range = [
                'from' => now()->$subFunc($value)->$startFunc(),
                'to' => now()->$subFunc($sub)->$endFunc(),
            ];
        } else {
            $range = [
                'from' => now()->$subFunc($value - $sub)->$startFunc(),
                'to' => now()->$endFunc(),
            ];
        }

//        if (defined('DATE_SCOPE_DEBUG'))
//            dd(collect($range)->transform(fn ($item) => $item->format('Y-m-d H:i:s'))->toArray());

        $createdColumnName = (self::CREATED_AT != 'created_at') ? self::CREATED_AT : config('date-scopes.created_column');

        return $query->whereBetween($createdColumnName, $range);
    }

    // START SECONDS
    public function scopeOfJustNow(Builder $query): Builder {return $query->ofLastSeconds(1, DateRange::INCLUSIVE);}
    public function scopeOfLastSecond(Builder $query): Builder {return $query->ofLastSeconds(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast15Seconds(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastSeconds(15, $customRange);}
    public function scopeOfLast30Seconds(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastSeconds(30, $customRange);}
    public function scopeOfLast45Seconds(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastSeconds(45, $customRange);}
    public function scopeOfLast60Seconds(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastSeconds(60, $customRange);}

    public function scopeOfLastSeconds(Builder $query, int $seconds, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('second', $seconds, $customRange);
    }

    // START MINUTES
    public function scopeOfLastMinute(Builder $query): Builder {return $query->ofLastMinutes(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast15Minutes(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMinutes(15, $customRange);}
    public function scopeOfLast30Minutes(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMinutes(30, $customRange);}
    public function scopeOfLast45Minutes(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMinutes(45, $customRange);}
    public function scopeOfLast60Minutes(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMinutes(60, $customRange);}

    public function scopeOfLastMinutes(Builder $query, int $minutes, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('minute', $minutes, $customRange);
    }

    // START HOURS
    public function scopeOfLastHour(Builder $query): Builder {return $query->ofLastHours(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast6Hours(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastHours(6, $customRange);}
    public function scopeOfLast12Hours(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastHours(12, $customRange);}
    public function scopeOfLast18Hours(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastHours(18, $customRange);}
    public function scopeOfLast24Hours(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastHours(24, $customRange);}

    public function scopeOfLastHours(Builder $query, int $hours, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('hour', $hours, $customRange);
    }

    // START DAYS
    public function scopeOfToday(Builder $query): Builder {return $query->ofLastDays(1, DateRange::INCLUSIVE);}
    public function scopeOfYesterday(Builder $query): Builder {return $query->ofLastDays(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast7Days(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastDays(7, $customRange);}
    public function scopeOfLast14Days(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastDays(14, $customRange);}
    public function scopeOfLast21Days(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastDays(21, $customRange);}
    public function scopeOfLast30Days(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastDays(30, $customRange);}

    public function scopeOfLastDays(Builder $query, int $days, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('day', $days, $customRange);
    }

    // START WEEKS
    public function scopeOfLastWeek(Builder $query): Builder {return $query->ofLastWeeks(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast2Weeks(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastWeeks(2, $customRange);}
    public function scopeOfLast3Weeks(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastWeeks(3, $customRange);}
    public function scopeOfLast4Weeks(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastWeeks(4, $customRange);}

    public function scopeOfLastWeeks(Builder $query, int $weeks, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('week', $weeks, $customRange);
    }

    // START MONTHS
    public function scopeOfLastMonth(Builder $query): Builder {return $query->ofLastMonths(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast3Months(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMonths(3, $customRange);}
    public function scopeOfLast6Months(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMonths(6, $customRange);}
    public function scopeOfLast9Months(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMonths(9, $customRange);}
    public function scopeOfLast12Months(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastMonths(12, $customRange);}

    public function scopeOfLastMonths(Builder $query, int $months, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('month', $months, $customRange);
    }

    // START QUARTER
    public function scopeOfLastQuarter(Builder $query): Builder {return $query->ofLastQuarters(1, DateRange::EXCLUSIVE);}
    public function scopeOfLast2Quarters(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastQuarters(2, $customRange);}
    public function scopeOfLast3Quarters(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastQuarters(3, $customRange);}
    public function scopeOfLast4Quarters(Builder $query, DateRange $customRange = null): Builder {return $query->ofLastQuarters(4, $customRange);}

    public function scopeOfLastQuarters(Builder $query, int $quarters, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('quarter', $quarters, $customRange);
    }

    // START YEARS
    public function scopeOfLastYear(Builder $query): Builder {return $query->ofLastYears(1, DateRange::EXCLUSIVE);}

    public function scopeOfLastYears(Builder $query, int $years, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('year', $years, $customRange);
    }

    // START DECADE
    public function scopeOfLastDecade(Builder $query): Builder {return $query->ofLastDecades(1, DateRange::EXCLUSIVE);}

    public function scopeOfLastDecades(Builder $query, int $decades, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('decade', $decades, $customRange);
    }

    // START CENTURIES
    public function scopeOfLastCentury(Builder $query): Builder {return $query->ofLastCenturies(1, DateRange::EXCLUSIVE);}

    public function scopeOfLastCenturies(Builder $query, int $centuries, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('century', $centuries, $customRange);
    }

    // START MILLENNIUM
    public function scopeOfLastMillennium(Builder $query): Builder {return $query->ofLastMillenniums(1, DateRange::EXCLUSIVE);}

    public function scopeOfLastMillenniums(Builder $query, int $millennium, DateRange $customRange = null): Builder
    {
        return $query->ofLastUnit('millennium', $millennium, $customRange);
    }

    // START CURRENT TO NOW

    public function scopeSecondToNow(Builder $query): Builder {return $query->ofLastSeconds(1, DateRange::INCLUSIVE);}
    public function scopeMinuteToNow(Builder $query): Builder {return $query->ofLastMinutes(1, DateRange::INCLUSIVE);}
    public function scopeHourToNow(Builder $query): Builder {return $query->ofLastHours(1, DateRange::INCLUSIVE);}
    public function scopeDayToNow(Builder $query): Builder {return $query->ofLastDays(1, DateRange::INCLUSIVE);}
    public function scopeWeekToDate(Builder $query): Builder {return $query->ofLastWeeks(1, DateRange::INCLUSIVE);}
    public function scopeMonthToDate(Builder $query): Builder {return $query->ofLastMonths(1, DateRange::INCLUSIVE);}
    public function scopeQuarterToDate(Builder $query): Builder {return $query->ofLastQuarters(1, DateRange::INCLUSIVE);}
    public function scopeYearToDate(Builder $query): Builder {return $query->ofLastYears(1, DateRange::INCLUSIVE);}
    public function scopeDecadeToDate(Builder $query): Builder {return $query->ofLastDecades(1, DateRange::INCLUSIVE);}
    public function scopeCenturyToDate(Builder $query): Builder {return $query->ofLastCenturies(1, DateRange::INCLUSIVE);}
    public function scopeMillenniumToDate(Builder $query): Builder {return $query->ofLastMillenniums(1, DateRange::INCLUSIVE);}
}
