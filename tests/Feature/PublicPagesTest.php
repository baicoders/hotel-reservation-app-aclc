<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_links_to_the_room_browser(): void
    {
        $this->get('/')->assertSee('href="' . url('/search-room') . '"', false);
    }

    public function test_home_shows_the_configured_version(): void
    {
        config(['app.version' => '2.1.0']);

        $this->get('/')->assertSee('Version 2.1.0');
    }

    public function test_home_hides_the_version_when_unset(): void
    {
        config(['app.version' => null]);

        $this->get('/')->assertDontSee('Version ');
    }

    public function test_wrong_password_is_reported_on_the_username_field(): void
    {
        User::factory()->create(['name' => 'Staff', 'password' => 'staff']);

        $this->from('/login')
            ->post('/login', ['username' => 'Staff', 'password' => 'wrong'])
            ->assertSessionHasErrors('username');
    }

    public function test_login_error_is_shown_under_the_username_field(): void
    {
        User::factory()->create(['name' => 'Staff', 'password' => 'staff']);

        $this->from('/login')
            ->followingRedirects()
            ->post('/login', ['username' => 'Staff', 'password' => 'wrong'])
            ->assertSee('id="username-error"', false);
    }

    public function test_login_error_is_not_repeated_in_the_corner(): void
    {
        User::factory()->create(['name' => 'Staff', 'password' => 'staff']);

        $this->from('/login')
            ->followingRedirects()
            ->post('/login', ['username' => 'Staff', 'password' => 'wrong'])
            ->assertDontSee('Please fix the following');
    }

    public function test_pages_without_inline_errors_keep_the_summary(): void
    {
        $this->withSession(['errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['room_id' => 'This room is no longer available.']))])
            ->get('/')
            ->assertSee('Please fix the following');
    }

    public function test_missing_password_is_a_field_error(): void
    {
        $this->from('/login')
            ->post('/login', ['username' => 'Staff'])
            ->assertSessionHasErrors('password');
    }

    public function test_right_password_lands_on_the_dashboard(): void
    {
        User::factory()->create(['name' => 'Staff', 'password' => 'staff']);

        $this->post('/login', ['username' => 'Staff', 'password' => 'staff'])->assertRedirect('/dashboard');
    }

    public function test_signed_in_staff_skip_the_login_form(): void
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/dashboard');
    }

    public function test_unknown_page_offers_the_room_browser(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('href="' . url('/search-room') . '"', false);
    }
}
