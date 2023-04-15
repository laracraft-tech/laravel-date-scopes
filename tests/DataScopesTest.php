<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use LaracraftTech\LaravelDateScopes\DateRange;
use LaracraftTech\LaravelDateScopes\Tests\Models\Transaction;

function getCreatedAtValues(): array
{
    return [
        // SECONDS
        ["created_at" => "2023-03-31 13:15:15"], // query transactions created just now
        ["created_at" => "2023-03-31 13:16:15"], // query transactions created during the last second
        ["created_at" => "2023-03-31 13:16:00"], // query transactions created during the last 15 seconds
        ["created_at" => "2023-03-24 00:00:00"], // query transactions created during the last 30 seconds
        ["created_at" => "2023-03-17 00:00:00"], // query transactions created during the last 45 seconds
        ["created_at" => "2023-01-13 00:00:00"], // query transactions created during the last 60 seconds
        ["created_at" => "2022-04-06 00:00:00"], // query transactions created during the last N seconds
        // MINUTES
        ["created_at" => "2022-06-12 00:00:00"], // query transactions created during the last minute
        ["created_at" => "1970-12-07 00:00:00"], // query transactions created during the last 15 minutes
        ["created_at" => "2002-03-18 00:00:00"], // query transactions created during the last 30 minutes
        ["created_at" => "2005-05-12 00:00:00"], // query transactions created during the last 45 minutes
        ["created_at" => "1995-03-26 00:00:00"], // query transactions created during the last 60 minutes
        ["created_at" => "1980-08-19 00:00:00"], // query transactions created during the last N minutes
        // HOURS
        ["created_at" => "2002-02-27 00:00:00"], // query transactions created during the last hour
        ["created_at" => "1993-04-29 00:00:00"], // query transactions created during the last 6 hours
        ["created_at" => "2000-12-12 00:00:00"], // query transactions created during the last 12 hours
        ["created_at" => "1978-01-28 00:00:00"], // query transactions created during the last 18 hours
        ["created_at" => "2001-02-11 00:00:00"], // query transactions created during the last 24 hours
        ["created_at" => "1977-04-19 00:00:00"], // query transactions created during the last N hours
        // DAYS
        ["created_at" => "1982-01-30 00:00:00"], // query transactions created today
        ["created_at" => "1985-08-28 00:00:00"], // query transactions created yesterday
        ["created_at" => "1973-10-15 00:00:00"], // query transactions created during the last 7 days
        ["created_at" => "2008-11-25 00:00:00"], // query transactions created during the last 21 days
        ["created_at" => "2020-09-20 00:00:00"], // query transactions created during the last 30 days
        ["created_at" => "1988-03-18 00:00:00"], // query transactions created during the last N days
        // WEEKS
        ["created_at" => "2004-06-19 00:00:00"], // query transactions created during the last week
        ["created_at" => "1978-12-01 00:00:00"], // query transactions created during the last 2 weeks
        ["created_at" => "1983-07-09 00:00:00"], // query transactions created during the last 3 weeks
        ["created_at" => "2006-05-25 00:00:00"], // query transactions created during the last 4 weeks
        ["created_at" => "2006-06-21 00:00:00"], // query transactions created during the last N weeks
        //MONTHS
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last month
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last 3 months
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the last 6 months
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last 9 months
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last 12 months
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the last N months
        //QUARTERS
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last quarter
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last 2 quarters
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the last 3 quarters
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last 4 quarters
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last N quarters
        //YEARS
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last year
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last N years
        //DECADES
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last year
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last N years
        //MILLENNIUMS
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the last year
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the last N years
        //toNow/toDate
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the start of the current second to now (not really usefull I guess)
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the start of the current minute to now
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the start of the current hour to now
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the start of the current day to now
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the start of the current week to now
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the start of the current month to now
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the start of the current quarter to now
        ["created_at" => "1987-06-07 00:00:00"], // query transactions created during the start of the current year to now
        ["created_at" => "2015-04-29 00:00:00"], // query transactions created during the start of the current decade to now
        ["created_at" => "1981-11-28 00:00:00"], // query transactions created during the start of the current millennium to
    ];
}

beforeEach(function () {
    // Set a fixed date and time for our tests (start on overflow date!)
    Carbon::setTestNow(Carbon::create(2023, 3, 31, 13, 15, 15));

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

    Transaction::ofJustNow()->get();
    Transaction::ofLast15Seconds(DateRange::INCLUSIVE)->get();
    Transaction::ofLast15Seconds(DateRange::EXCLUSIVE)->get();
    dd();
});

it('retrieves transactions of last x seconds', function () {
//    expect(Transaction::ofJustNow()->get())->toHaveCount(1);
//    expect(Transaction::ofLastSecond()->get())->toHaveCount(1);
//    expect(Transaction::ofLast15Seconds(DateRange::INCLUSIVE)->get())->toHaveCount(2);
//    expect(Transaction::ofLast15Seconds(DateRange::EXCLUSIVE)->get())->toHaveCount(2);


//    Transaction::ofToday()->get();
//    Transaction::ofYesterday()->get();
//    Transaction::ofLast7Days()->get();
});
//
it('covers all cases', function () {
    //TODO
    // Write tests for all kind of scopes
    // Create a db with fixed date values
    // and test with: Carbon::setTestNow('2023-01-01');
    expect(true)->toBeTrue();
});
