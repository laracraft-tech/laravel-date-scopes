<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use LaracraftTech\LaravelDateScopes\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->beforeEach(function () {
    ray()->clearAll();
})->in(__DIR__);
