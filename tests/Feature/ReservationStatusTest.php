<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationStatusTest extends TestCase
{
    use RefreshDatabase;

    private function change(Reservation $reservation, string $status): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/reservations/update-status/' . $reservation->id, ['status' => $status]);
    }

    private function stay(string $roomStatus, string $reservationStatus): Reservation
    {
        $room = Room::factory()->create(['status' => $roomStatus]);

        return Reservation::factory()->create(['room_id' => $room->id, 'status' => $reservationStatus]);
    }

    public function test_check_in_marks_the_room_unavailable(): void
    {
        $reservation = $this->stay('available', 'confirmed');
        $this->change($reservation, 'checked_in');

        $this->assertSame('unavailable', Room::find($reservation->room_id)->status);
    }

    public function test_check_in_keeps_a_room_in_maintenance(): void
    {
        $reservation = $this->stay('maintenance', 'confirmed');
        $this->change($reservation, 'checked_in');

        $this->assertSame('maintenance', Room::find($reservation->room_id)->status);
    }

    public function test_check_out_frees_the_room(): void
    {
        $reservation = $this->stay('unavailable', 'checked_in');
        $this->change($reservation, 'checked_out');

        $this->assertSame('available', Room::find($reservation->room_id)->status);
    }

    public function test_cancelling_frees_the_room(): void
    {
        $reservation = $this->stay('unavailable', 'checked_in');
        $this->change($reservation, 'cancelled');

        $this->assertSame('available', Room::find($reservation->room_id)->status);
    }

    public function test_check_out_keeps_the_room_another_guest_is_checked_in_to(): void
    {
        $reservation = $this->stay('unavailable', 'checked_in');
        Reservation::factory()->create(['room_id' => $reservation->room_id, 'status' => 'checked_in']);
        $this->change($reservation, 'checked_out');

        $this->assertSame('unavailable', Room::find($reservation->room_id)->status);
    }

    public function test_check_out_keeps_a_room_in_maintenance(): void
    {
        $reservation = $this->stay('maintenance', 'checked_in');
        $this->change($reservation, 'checked_out');

        $this->assertSame('maintenance', Room::find($reservation->room_id)->status);
    }

    public function test_moving_a_checked_in_stay_back_to_confirmed_frees_the_room(): void
    {
        $reservation = $this->stay('unavailable', 'checked_in');
        $this->change($reservation, 'confirmed');

        $this->assertSame('available', Room::find($reservation->room_id)->status);
    }

    public function test_confirming_leaves_the_room_alone(): void
    {
        $reservation = $this->stay('available', 'pending');
        $this->change($reservation, 'confirmed');

        $this->assertSame('available', Room::find($reservation->room_id)->status);
    }
}
