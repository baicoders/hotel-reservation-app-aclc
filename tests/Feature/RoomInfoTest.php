<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomInfoTest extends TestCase
{
    use RefreshDatabase;

    private function visit(Room $room)
    {
        return $this->actingAs(User::factory()->create())->get('/rooms/details/' . $room->id);
    }

    public function test_checked_out_reservations_are_listed(): void
    {
        $room = Room::factory()->create();
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed']);
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'checked_out']);

        $this->visit($room)->assertViewHas('reservations', fn ($rows) => $rows->count() === 2);
    }

    public function test_reservation_rows_link_to_their_details(): void
    {
        $room = Room::factory()->create();
        $reservation = Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed']);

        $this->visit($room)->assertSee(url('/reservations/details/' . $reservation->id));
    }

    public function test_delete_warning_states_the_reservation_count(): void
    {
        $room = Room::factory()->create();
        Reservation::factory()->count(2)->create(['room_id' => $room->id]);

        $this->visit($room)->assertSee('its 2 reservations');
    }

    public function test_room_without_photos_says_so(): void
    {
        $this->visit(Room::factory()->create())->assertSee('No photos yet');
    }

    public function test_available_room_offers_booking(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->visit($room)->assertSee(url('/guest/rooms/reserve') . '?room_id=' . $room->id);
    }

    public function test_room_in_maintenance_does_not_offer_booking(): void
    {
        $room = Room::factory()->create(['status' => 'maintenance']);

        $this->visit($room)->assertDontSee('Book this room');
    }
}
