<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_must_log_in_to_access_the_admin_panel(): void
    {
        foreach (['/admin', '/admin/dashboard', '/admin/settings'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        $this->post('/admin/settings', ['facebook_url' => 'https://example.com'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('settings', 0);
    }

    public function test_verified_users_without_the_admin_role_cannot_access_or_change_admin_data(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']));

        foreach (['/admin', '/admin/dashboard', '/admin/settings'] as $url) {
            $this->get($url)->assertForbidden();
        }

        $this->post('/admin/settings', ['facebook_url' => 'https://example.com'])
            ->assertForbidden();

        $this->assertDatabaseCount('settings', 0);
    }

    public function test_admins_must_verify_their_email_before_accessing_the_panel(): void
    {
        $this->actingAs(User::factory()->unverified()->create(['role' => 'admin']));

        foreach (['/admin', '/admin/dashboard', '/admin/settings'] as $url) {
            $this->get($url)->assertRedirect(route('verification.notice'));
        }

        $this->post('/admin/settings', ['facebook_url' => 'https://example.com'])
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseCount('settings', 0);
    }

    public function test_verified_admins_can_access_the_panel_and_update_settings(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get('/admin')->assertRedirect(route('admin.dashboard'));

        foreach (['dashboard', 'pets', 'donations', 'adoption-requests', 'settings', 'bingo', 'profile'] as $page) {
            $this->get('/admin/'.$page)->assertOk();
        }

        $this->post('/admin/settings', ['facebook_url' => 'https://example.com'])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('settings', ['key' => 'facebook_url', 'value' => 'https://example.com']);
    }

    public function test_public_pages_remain_accessible_to_guests(): void
    {
        $pet = Pet::factory()->create();

        foreach (['/', '/mascotas', '/mascotas/'.$pet->slug, '/donar', '/acerca-de', '/colabora'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->assertGuest();
    }
}
