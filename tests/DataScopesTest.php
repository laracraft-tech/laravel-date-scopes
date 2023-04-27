<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use LaracraftTech\LaravelDateScopes\DateRange;
use LaracraftTech\LaravelDateScopes\Tests\Models\Transaction;

function getCreatedAtValues(): array
{
    $start = '2023-03-31 13:15:15';
    Carbon::setTestNow(Carbon::parse($start));

    return [
        ['created_at' => $start],
        ['created_at' => '2023-03-31 13:15:14'],
        ['created_at' => '2023-03-31 13:15:00'],
        ['created_at' => '2023-03-31 13:13:15'],
        ['created_at' => '2023-03-31 13:14:45'],
        ['created_at' => '2023-03-31 13:14:30'],
        ['created_at' => '2023-03-31 13:14:15'],
        ['created_at' => '2023-03-31 12:45:00'],
        ['created_at' => '2023-03-31 12:30:00'],
        ['created_at' => '2023-03-31 12:15:00'],
        ['created_at' => '2023-03-31 11:15:00'],
        ['created_at' => '2023-03-31 01:00:00'],
        ['created_at' => '2023-03-30 19:00:00'],
        ['created_at' => '2023-03-30 13:00:00'],
        ['created_at' => '2023-03-29 13:00:00'],
        ['created_at' => '2023-03-20 00:00:00'],
        ['created_at' => '2023-03-17 00:00:00'],
        ['created_at' => '2023-03-10 00:00:00'],
        ['created_at' => '2023-03-01 00:00:00'],
        ['created_at' => '2023-02-11 00:00:00'],
        ['created_at' => '2022-12-01 00:00:00'],
        ['created_at' => '2022-09-01 00:00:00'],
        ['created_at' => '2022-06-01 00:00:00'],
        ['created_at' => '2022-03-01 00:00:00'],
        ['created_at' => '2021-03-01 00:00:00'],
        ['created_at' => '2010-01-01 00:00:00'],
        ['created_at' => '2000-01-01 00:00:00'],
        ['created_at' => '1801-01-01 00:00:00'],
        ['created_at' => '0001-01-01 00:00:00'],
    ];
}

beforeEach(function () {
    config(['date-scopes.default_range' => DateRange::EXCLUSIVE->value]);

    Schema::create('transactions', function (Blueprint $blueprint) {
        $blueprint->id();
        $blueprint->string('col1');
        $blueprint->integer('col2');
        $blueprint->timestamps();
    });

    $createdAtValues = getCreatedAtValues();

    Transaction::factory()
        ->count(count($createdAtValues))
        ->state(new Sequence(...$createdAtValues))
        ->create();
});

it('retrieves transactions of last seconds', function () {
    expect(Transaction::ofJustNow()->get())->toHaveCount(1)
        ->and(Transaction::ofLastSecond()->get())->toHaveCount(1)
        ->and(Transaction::ofLast15Seconds()->get())->toHaveCount(2)
        ->and(Transaction::ofLast30Seconds()->get())->toHaveCount(3)
        ->and(Transaction::ofLast45Seconds()->get())->toHaveCount(4)
        ->and(Transaction::ofLast60Seconds()->get())->toHaveCount(5)
        ->and(Transaction::ofLastSeconds(120)->get())->toHaveCount(6);
});

it('retrieves transactions of last minutes', function () {
    expect(Transaction::ofLastMinute()->get())->toHaveCount(3)
        ->and(Transaction::ofLast15Minutes()->get())->toHaveCount(4)
        ->and(Transaction::ofLast30Minutes()->get())->toHaveCount(5)
        ->and(Transaction::ofLast45Minutes()->get())->toHaveCount(6)
        ->and(Transaction::ofLast60Minutes()->get())->toHaveCount(7)
        ->and(Transaction::ofLastMinutes(120)->get())->toHaveCount(8);
});

it('retrieves transactions of last hours', function () {
    expect(Transaction::ofLastHour()->get())->toHaveCount(3)
        ->and(Transaction::ofLast6Hours()->get())->toHaveCount(4)
        ->and(Transaction::ofLast12Hours()->get())->toHaveCount(5)
        ->and(Transaction::ofLast18Hours()->get())->toHaveCount(6)
        ->and(Transaction::ofLast24Hours()->get())->toHaveCount(7)
        ->and(Transaction::ofLastHours(48)->get())->toHaveCount(8);
});

it('retrieves transactions of last days', function () {
    expect(Transaction::ofToday()->get())->toHaveCount(12)
        ->and(Transaction::ofYesterday()->get())->toHaveCount(2)
        ->and(Transaction::ofLast7Days()->get())->toHaveCount(3)
        ->and(Transaction::ofLast14Days()->get())->toHaveCount(5)
        ->and(Transaction::ofLast21Days()->get())->toHaveCount(6)
        ->and(Transaction::ofLast30Days()->get())->toHaveCount(7)
        ->and(Transaction::ofLastDays(48)->get())->toHaveCount(8);
});

it('retrieves transactions of last weeks', function () {
    expect(Transaction::ofLastWeek()->get())->toHaveCount(1)
        ->and(Transaction::ofLast2Weeks()->get())->toHaveCount(2)
        ->and(Transaction::ofLast3Weeks()->get())->toHaveCount(3)
        ->and(Transaction::ofLast4Weeks()->get())->toHaveCount(4)
        ->and(Transaction::ofLastWeeks(8)->get())->toHaveCount(5);
});

it('retrieves transactions of last months', function () {
    expect(Transaction::ofLastMonth()->get())->toHaveCount(1)
        ->and(Transaction::ofLast3Months()->get())->toHaveCount(2)
        ->and(Transaction::ofLast6Months()->get())->toHaveCount(3)
        ->and(Transaction::ofLast9Months()->get())->toHaveCount(4)
        ->and(Transaction::ofLast12Months()->get())->toHaveCount(5)
        ->and(Transaction::ofLastMonths(24)->get())->toHaveCount(6);
});

it('retrieves transactions of last quarters', function () {
    expect(Transaction::ofLastQuarter()->get())->toHaveCount(1)
        ->and(Transaction::ofLast2Quarters()->get())->toHaveCount(2)
        ->and(Transaction::ofLast3Quarters()->get())->toHaveCount(3)
        ->and(Transaction::ofLast4Quarters()->get())->toHaveCount(4)
        ->and(Transaction::ofLastQuarters(8)->get())->toHaveCount(5);
});

it('retrieves transactions of last years', function () {
    expect(Transaction::ofLastYear()->get())->toHaveCount(4)
        ->and(Transaction::ofLastYears(2)->get())->toHaveCount(5);
});

it('retrieves transactions of last decades', function () {
    expect(Transaction::ofLastDecade()->get())->toHaveCount(1)
        ->and(Transaction::ofLastDecades(2)->get())->toHaveCount(2);
});

it('retrieves transactions of last centuries', function () {
    expect(Transaction::ofLastCentury()->get())->toHaveCount(1)
        ->and(Transaction::ofLastCenturies(2)->get())->toHaveCount(2);
});

it('retrieves transactions of last millenniums', function () {
    expect(Transaction::ofLastMillennium()->get())->toHaveCount(2)
        ->and(Transaction::ofLastMillenniums(2)->get())->toHaveCount(3);
});

it('retrieves transactions of toNow/toDate', function () {
    expect(Transaction::secondToNow()->get())->toHaveCount(1)
        ->and(Transaction::minuteToNow()->get())->toHaveCount(3)
        ->and(Transaction::hourToNow()->get())->toHaveCount(7)
        ->and(Transaction::dayToNow()->get())->toHaveCount(12)
        ->and(Transaction::weekToDate()->get())->toHaveCount(15)
        ->and(Transaction::monthToDate()->get())->toHaveCount(19)
        ->and(Transaction::quarterToDate()->get())->toHaveCount(20)
        ->and(Transaction::yearToDate()->get())->toHaveCount(20)
        ->and(Transaction::decadeToDate()->get())->toHaveCount(25)
        ->and(Transaction::centuryToDate()->get())->toHaveCount(26)
        ->and(Transaction::millenniumToDate()->get())->toHaveCount(26);
});

it('retrieves transactions of last x (inclusive config date range)', function () {
    config(['date-scopes.default_range' => DateRange::INCLUSIVE->value]);

    expect(Transaction::ofLast15Minutes()->get())->toHaveCount(7)
        ->and(Transaction::ofLast12Hours()->get())->toHaveCount(11)
        ->and(Transaction::ofLast21Days()->get())->toHaveCount(17)
        ->and(Transaction::ofLast3Weeks()->get())->toHaveCount(17)
        ->and(Transaction::ofLast12Months()->get())->toHaveCount(23)
        ->and(Transaction::ofLastQuarters(8)->get())->toHaveCount(24)
        ->and(Transaction::ofLastYear()->get())->toHaveCount(4)
        ->and(Transaction::ofLastDecade()->get())->toHaveCount(1)
        ->and(Transaction::ofLastCentury()->get())->toHaveCount(1)
        ->and(Transaction::ofLastMillennium()->get())->toHaveCount(2);
});

it('retrieves transactions of last x (inclusive fluent date range)', function () {
    expect(Transaction::ofLast15Minutes(DateRange::INCLUSIVE)->get())->toHaveCount(7)
        ->and(Transaction::ofLast12Hours(DateRange::INCLUSIVE)->get())->toHaveCount(11)
        ->and(Transaction::ofLast21Days(DateRange::INCLUSIVE)->get())->toHaveCount(17)
        ->and(Transaction::ofLast3Weeks(DateRange::INCLUSIVE)->get())->toHaveCount(17)
        ->and(Transaction::ofLast12Months(DateRange::INCLUSIVE)->get())->toHaveCount(23)
        ->and(Transaction::ofLastQuarters(8, DateRange::INCLUSIVE)->get())->toHaveCount(24);
});
