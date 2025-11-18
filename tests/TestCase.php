<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure MongoDB is the default connection for tests
        $this->app->make(Repository::class)->set('database.default', 'mongodb');

        // Disable migration batching/tracking for MongoDB
        $this->app->make(Repository::class)->set('database.migrations', 'migrations_mongodb');
    }
}
