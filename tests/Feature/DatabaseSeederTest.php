<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_seeding_does_not_create_demo_users_or_payment_details(): void
    {
        $this->app['env'] = 'production';

        $this->artisan('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true])
            ->assertSuccessful();

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('pets', 0);
        $this->assertDatabaseCount('payment_methods', 0);
        $this->assertDatabaseCount('donations', 0);
    }
}
