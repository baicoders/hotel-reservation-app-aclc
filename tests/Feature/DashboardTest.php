<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_total_counts_every_room(): void
    {
        Room::factory()->count(7)->create();

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('stats', fn ($stats) => $stats['rooms'] === 7);
    }

    public function test_available_room_total_counts_only_available_rooms(): void
    {
        Room::factory()->count(3)->create(['status' => 'available']);
        Room::factory()->count(2)->create(['status' => 'maintenance']);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('stats', fn ($stats) => $stats['available'] === 3);
    }

    public function test_active_stays_count_confirmed_and_checked_in(): void
    {
        Reservation::factory()->create(['status' => 'confirmed']);
        Reservation::factory()->create(['status' => 'checked_in']);
        Reservation::factory()->create(['status' => 'checked_out']);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('stats', fn ($stats) => $stats['active'] === 2);
    }

    public function test_guest_total_counts_every_guest(): void
    {
        Guest::factory()->count(6)->create();

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('stats', fn ($stats) => $stats['guests'] === 6);
    }

    public function test_recent_reservations_show_the_room_number(): void
    {
        $room = Room::factory()->create(['rm_number' => 'R-714']);
        Reservation::factory()->create(['room_id' => $room->id]);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('latestReservations', fn ($rows) => $rows->first()->room_number === 'R-714');
    }

    public function test_rooms_not_available_lists_rooms_out_of_service(): void
    {
        Room::factory()->create(['rm_number' => 'M-1', 'status' => 'maintenance']);
        Room::factory()->create(['rm_number' => 'A-1', 'status' => 'available']);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertViewHas('unavailableRooms', fn ($rooms) => $rooms->pluck('rm_number')->all() === ['M-1']);
    }

    public function test_dashboard_link_is_marked_as_current_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertSee('href="' . url('/dashboard') . '" aria-current="page"', false);
    }

    public function test_visitor_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
