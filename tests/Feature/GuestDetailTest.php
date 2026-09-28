<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestDetailTest extends TestCase
{
    use RefreshDatabase;

    private function visit(Guest $guest)
    {
        return $this->actingAs(User::factory()->create())->get('/guest/details/' . $guest->id);
    }

    public function test_profile_shows_the_address(): void
    {
        $guest = Guest::factory()->create(['address' => '12 Rizal Street, Ormoc']);

        $this->visit($guest)->assertSee('12 Rizal Street, Ormoc');
    }

    public function test_history_keeps_the_reservation_status(): void
    {
        $guest = Guest::factory()->create();
        $room = Room::factory()->create(['status' => 'maintenance']);
        Reservation::factory()->create(['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'checked_out']);

        $this->visit($guest)->assertViewHas('bookings', fn ($rows) => $rows->first()->status === 'checked_out');
    }

    public function test_history_carries_the_room_status(): void
    {
        $guest = Guest::factory()->create();
        $room = Room::factory()->create(['status' => 'maintenance']);
        Reservation::factory()->create(['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'checked_out']);

        $this->visit($guest)->assertViewHas('bookings', fn ($rows) => $rows->first()->room_status === 'maintenance');
    }

    public function test_history_links_to_the_reservation(): void
    {
        $guest = Guest::factory()->create();
        Room::factory()->count(3)->create();
        $reservation = Reservation::factory()->create(['guest_id' => $guest->id]);

        $this->visit($guest)->assertSee(url('/reservations/details/' . $reservation->id));
    }

    public function test_dead_booking_link_is_gone(): void
    {
        $this->visit(Guest::factory()->create())->assertDontSee('/bookings/create');
    }

    public function test_delete_warning_states_the_reservation_count(): void
    {
        $guest = Guest::factory()->create();
        Reservation::factory()->count(2)->create(['guest_id' => $guest->id]);

        $this->visit($guest)->assertSee('their 2 reservations');
    }

    public function test_guest_without_reservations_says_so(): void
    {
        $this->visit(Guest::factory()->create())->assertSee('No reservations yet');
    }
}
