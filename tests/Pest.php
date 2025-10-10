<?php

declare(strict_types=1);

use CodeLieutenant\LaravelPgEnum\Tests\TestCase;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;

uses(TestCase::class)->in(__DIR__);

function assertEnumExists(string $name): void
{
    $values = DB::select('SELECT * FROM pg_type where typname = ?', [$name]);
    assertNotEmpty($values);
}

function assertEnumNotExists(string $name): void
{
    $values = DB::select('SELECT * FROM pg_type where typname = ?', [$name]);
    assertEmpty($values);
}

function assertEnumHasValues(string $name, array $values): void
{
    $vals = Str::of(DB::select("SELECT enum_range(null::$name)")[0]->enum_range)
        ->ltrim('{')
        ->rtrim('}')
        ->explode(',')
        ->toArray();

    assertEquals($values, $vals);
}
