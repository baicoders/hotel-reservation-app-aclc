<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function day(int $offset): string
    {
        return today()->addDays($offset)->toDateString();
    }

    private function book(Room $room, array $overrides = [])
    {
        return $this->from('/guest/rooms/reserve?room_id=' . $room->id)->post('/guest/rooms/booking-room', array_merge([
            'room_id' => $room->id,
            'check_in_date' => $this->day(10),
            'check_out_date' => $this->day(13),
            'name' => 'Lena Cruz',
            'email' => 'lena.cruz@example.com',
            'phone' => '0917 555 0100',
            'address' => 'Ormoc City',
            'amount' => '300.00',
            'payment_method' => 'cash',
            'payment_date' => $this->day(0),
        ], $overrides));
    }

    private function room(string $status = 'available'): Room
    {
        return Room::factory()->create(['rm_price_per_night' => 100, 'status' => $status]);
    }

    public function test_three_night_stay_saves_the_chosen_dates(): void
    {
        $room = $this->room();
        $this->book($room);

        $this->assertDatabaseHas('reservations', ['room_id' => $room->id, 'check_in_date' => $this->day(10), 'check_out_date' => $this->day(13)]);
    }

    public function test_three_night_stay_charges_three_nights(): void
    {
        $this->book($this->room());

        $this->assertSame('300.00', Payment::first()->amount);
    }

    public function test_booking_leaves_the_room_status_alone(): void
    {
        $room = $this->room();
        $this->book($room);

        $this->assertSame('available', $room->fresh()->status);
    }

    public function test_occupied_room_can_be_booked_for_later_dates(): void
    {
        $room = $this->room('unavailable');
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'checked_in', 'check_in_date' => $this->day(0), 'check_out_date' => $this->day(2)]);

        $this->book($room)->assertSessionHasNoErrors();
    }

    public function test_room_in_maintenance_cannot_be_booked(): void
    {
        $this->book($this->room('maintenance'))->assertSessionHasErrors('room_id');
    }

    public function test_one_night_amount_for_three_nights_is_rejected(): void
    {
        $this->book($this->room(), ['amount' => '100.00'])->assertSessionHasErrors(['amount' => 'The amount must equal the total of $300.00.']);
    }

    public function test_check_out_on_the_check_in_day_is_rejected(): void
    {
        $this->book($this->room(), ['check_out_date' => $this->day(10)])->assertSessionHasErrors('check_out_date');
    }

    public function test_check_in_before_today_is_rejected(): void
    {
        $this->book($this->room(), ['check_in_date' => $this->day(-1)])->assertSessionHasErrors('check_in_date');
    }

    public function test_stay_of_thirty_one_nights_is_rejected(): void
    {
        $this->book($this->room(), ['check_out_date' => $this->day(41), 'amount' => '3100.00'])->assertSessionHasErrors('check_out_date');
    }

    public function test_overlapping_stay_is_rejected(): void
    {
        $room = $this->room();
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed', 'check_in_date' => $this->day(12), 'check_out_date' => $this->day(15)]);

        $this->book($room)->assertSessionHasErrors('check_in_date');
    }

    public function test_overlapping_stay_saves_nothing(): void
    {
        $room = $this->room();
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed', 'check_in_date' => $this->day(12), 'check_out_date' => $this->day(15)]);
        $this->book($room);

        $this->assertSame(0, Payment::count());
    }

    public function test_stay_starting_on_a_check_out_day_is_accepted(): void
    {
        $room = $this->room();
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'checked_out', 'check_in_date' => $this->day(8), 'check_out_date' => $this->day(10)]);
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed', 'check_in_date' => $this->day(13), 'check_out_date' => $this->day(14)]);

        $this->book($room)->assertSessionHasNoErrors();
    }

    public function test_returning_guest_is_reused(): void
    {
        Guest::factory()->create(['email' => 'lena.cruz@example.com']);
        $this->book($this->room());

        $this->assertSame(1, Guest::count());
    }

    public function test_receipt_never_shows_stored_details_of_another_guest(): void
    {
        Guest::factory()->create(['email' => 'lena.cruz@example.com', 'phone_number' => '0999 111 2222', 'address' => '7 Private Lane']);

        $this->book($this->room())->assertDontSee('7 Private Lane');
    }

    public function test_form_fills_dates_from_the_query(): void
    {
        $room = $this->room();

        $this->get('/guest/rooms/reserve?room_id=' . $room->id . '&check_in=' . $this->day(4) . '&check_out=' . $this->day(6))
            ->assertSee('value="' . $this->day(6) . '"', false);
    }

    public function test_form_ignores_array_dates_in_the_query(): void
    {
        $room = $this->room();

        $this->get('/guest/rooms/reserve?room_id=' . $room->id . '&check_in[]=x')->assertOk();
    }

    public function test_form_without_room_goes_to_the_room_browser(): void
    {
        $this->get('/guest/rooms/reserve')->assertRedirect('/search-room');
    }

    public function test_form_for_room_in_maintenance_goes_to_the_room_page(): void
    {
        $room = $this->room('maintenance');

        $this->get('/guest/rooms/reserve?room_id=' . $room->id)->assertRedirect('/guest/rooms/details/' . $room->id);
    }
}
