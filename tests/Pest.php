<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Tests\TestCase;

// Define SIGTERM if not already defined (for environments where pcntl extension is not available)
if (! defined('SIGTERM')) {
    define('SIGTERM', 15);
}

pest()->extend(TestCase::class)
    ->beforeEach(function (): void {
        Str::createRandomStringsNormally();
        Str::createUuidsNormally();
        Http::preventStrayRequests();
        Process::preventStrayProcesses();
        Sleep::fake();

        $this->freezeTime();

        // Refresh MongoDB database by dropping all collections
        $db = $this->app->make('db')->connection('mongodb')->getMongoClient();
        $database = $this->app->make('db')->connection('mongodb')->getDatabaseName();

        try {
            $db->selectDatabase($database)->drop();
        } catch (Exception) {
            // Database might not exist yet, that's okay
        }

        $this->artisan('migrate', [
            '--database' => 'mongodb',
            '--force' => true,
        ]);
    })
    ->afterEach(function (): void {
        // Clean up after each test by dropping all collections
        try {
            $db = $this->app->make('db')->connection('mongodb')->getMongoClient();
            $database = $this->app->make('db')->connection('mongodb')->getDatabaseName();
            $db->selectDatabase($database)->drop();
        } catch (Exception) {
            // Ignore errors during cleanup
        }
    })
    ->in('Browser', 'Feature', 'Unit');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function something(): void
{
    // ..
}
