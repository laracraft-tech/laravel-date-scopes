<?php

namespace LaracraftTech\LaravelDateScopes;

/**
 * In statistics, when asking for "the last 7 days", the current day may or may not be included
 * in the calculation depending on the context and the specific requirements of the analysis.
 *
 * If you want to include the current day in the calculation, you would generally use an inclusive range,
 * meaning that you would include records created on the current day as well as records
 * created in the previous 6 days.
 *
 * If you want to exclude the current day in the calculation, you would generally use an exclusive range,
 * meaning that you would include records created in the previous 7 days,
 * but not records created on the current day.
 *
 * Ultimately, it depends on the context and what you're trying to achieve with your data.
 * It's always a good idea to clarify the requirements and expectations with stakeholders
 * to ensure that you're including or excluding the correct records.
 *
 * The same concept applies to other time intervals like weeks, months, quarters, and years etc.
 */
enum DateRange: string
{
    case INCLUSIVE = 'inclusive';
    case EXCLUSIVE = 'exclusive';
}
