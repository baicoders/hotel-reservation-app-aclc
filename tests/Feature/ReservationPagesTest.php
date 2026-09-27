<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationPagesTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create();
    }

    private function ids($page): array
    {
        return collect($page->items())->pluck('id')->sort()->values()->all();
    }

    public function test_oldest_of_sixteen_is_on_page_two(): void
    {
        foreach (range(1, 16) as $days) {
            $last = Reservation::factory()->create(['created_at' => now()->subDays($days)]);
        }

        $this->actingAs($this->staff())
            ->get('/reservations?page=2')
            ->assertViewHas('reservations', fn ($page) => $this->ids($page) === [$last->id]);
    }

    public function test_cancelled_filter_matches_both_spellings(): void
    {
        $a = Reservation::factory()->create(['status' => 'cancelled']);
        $b = Reservation::factory()->create(['status' => 'canceled']);
        Reservation::factory()->create(['status' => 'confirmed']);

        $this->actingAs($this->staff())
            ->get('/reservations?status=cancelled')
            ->assertViewHas('reservations', fn ($page) => $this->ids($page) === [$a->id, $b->id]);
    }

    public function test_detail_shows_the_payment(): void
    {
        $reservation = Reservation::factory()->create();
        Payment::factory()->create(['reservation_id' => $reservation->id, 'amount' => 123.45, 'payment_method' => 'cash']);

        $this->actingAs($this->staff())
            ->get('/reservations/details/' . $reservation->id)
            ->assertSee('$123.45');
    }

    public function test_detail_without_payment_says_so(): void
    {
        $reservation = Reservation::factory()->create();

        $this->actingAs($this->staff())
            ->get('/reservations/details/' . $reservation->id)
            ->assertSee('No payment recorded');
    }

    public function test_detail_links_to_the_guest(): void
    {
        $reservation = Reservation::factory()->create();

        $this->actingAs($this->staff())
            ->get('/reservations/details/' . $reservation->id)
            ->assertSee(url('/guest/details/' . $reservation->guest_id));
    }

    public function test_detail_links_to_the_room(): void
    {
        $reservation = Reservation::factory()->create();

        $this->actingAs($this->staff())
            ->get('/reservations/details/' . $reservation->id)
            ->assertSee(url('/rooms/details/' . $reservation->room_id));
    }

    public function test_check_in_marks_the_room_unavailable(): void
    {
        $room = Room::factory()->create(['status' => 'available']);
        $reservation = Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed']);

        $this->actingAs($this->staff())
            ->post('/reservations/update-status/' . $reservation->id, ['status' => 'checked_in']);

        $this->assertSame('unavailable', $room->fresh()->status);
    }
}
