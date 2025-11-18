<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait RefreshMongoDatabase
{
    public function tearDown(): void
    {
        if (RefreshDatabaseState::$migrated) {
            $this->refreshTestDatabase();
        }

        parent::tearDown();
    }

    protected function refreshDatabase(): void
    {
        $this->usingInMemoryDatabase() ?: $this->refreshTestDatabase();
    }

    protected function refreshTestDatabase(): void
    {
        $database = $this->app->make('db')->connection('mongodb')->getDatabaseName();
        $this->app->make('db')->connection('mongodb')->dropDatabase($database);

        // Run migrations on test database
        $this->artisan('migrate', [
            '--database' => 'mongodb',
            '--force' => true,
        ]);

        RefreshDatabaseState::$migrated = true;
    }

    protected function usingInMemoryDatabase(): bool
    {
        return $this->app->make('db')->connection('mongodb')->getDatabaseName() === ':memory:';
    }
}
