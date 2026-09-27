<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestBrowseTest extends TestCase
{
    use RefreshDatabase;

    private function numbers($page): array
    {
        return collect($page->items())->pluck('rm_number')->all();
    }

    public function test_only_available_rooms_are_listed(): void
    {
        Room::factory()->create(['rm_number' => 'A1', 'status' => 'available']);
        Room::factory()->create(['rm_number' => 'M1', 'status' => 'maintenance']);

        $this->get('/search-room')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['A1']);
    }

    public function test_card_reserve_opens_the_booking_form(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->get('/search-room')->assertSee(url('/guest/rooms/reserve') . '?room_id=' . $room->id);
    }

    public function test_type_filter_lists_only_that_type(): void
    {
        Room::factory()->create(['rm_number' => 'S1', 'rm_type' => 'Suite', 'status' => 'available']);
        Room::factory()->create(['rm_number' => 'D1', 'rm_type' => 'Double', 'status' => 'available']);

        $this->get('/search-room?type=Suite')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['S1']);
    }

    public function test_type_options_come_from_available_rooms(): void
    {
        Room::factory()->create(['rm_type' => 'Suite', 'status' => 'available']);
        Room::factory()->create(['rm_type' => 'Penthouse', 'status' => 'maintenance']);

        $this->get('/search-room')->assertViewHas('types', fn ($types) => $types->all() === ['Suite']);
    }

    public function test_price_sort_low_to_high(): void
    {
        Room::factory()->create(['rm_number' => 'P2', 'rm_price_per_night' => 200, 'status' => 'available']);
        Room::factory()->create(['rm_number' => 'P1', 'rm_price_per_night' => 90, 'status' => 'available']);

        $this->get('/search-room?sort=price_asc')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['P1', 'P2']);
    }

    public function test_price_sort_high_to_low(): void
    {
        Room::factory()->create(['rm_number' => 'P1', 'rm_price_per_night' => 90, 'status' => 'available']);
        Room::factory()->create(['rm_number' => 'P2', 'rm_price_per_night' => 200, 'status' => 'available']);

        $this->get('/search-room?sort=price_desc')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['P2', 'P1']);
    }

    public function test_thirteenth_room_is_on_page_two(): void
    {
        foreach (range(1, 13) as $n) {
            Room::factory()->create(['rm_number' => sprintf('R%02d', $n), 'status' => 'available']);
        }

        $this->get('/search-room?page=2')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['R13']);
    }

    public function test_page_past_the_end_links_to_page_one(): void
    {
        Room::factory()->create(['status' => 'available']);

        $this->get('/search-room?page=99')->assertSee('past the last page');
    }

    private function day(int $offset): string
    {
        return today()->addDays($offset)->toDateString();
    }

    public function test_default_search_leaves_out_rooms_booked_tonight(): void
    {
        $booked = Room::factory()->create(['rm_number' => 'B1', 'status' => 'available']);
        Room::factory()->create(['rm_number' => 'F1', 'status' => 'available']);
        \App\Models\Reservation::factory()->create(['room_id' => $booked->id, 'status' => 'confirmed', 'check_in_date' => $this->day(0), 'check_out_date' => $this->day(2)]);

        $this->get('/search-room')->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['F1']);
    }

    public function test_dated_search_lists_rooms_whose_stay_ends_on_the_check_in_day(): void
    {
        $room = Room::factory()->create(['rm_number' => 'E1', 'status' => 'available']);
        \App\Models\Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed', 'check_in_date' => $this->day(3), 'check_out_date' => $this->day(5)]);

        $this->get('/search-room?check_in=' . $this->day(5) . '&check_out=' . $this->day(7))
            ->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['E1']);
    }

    public function test_occupied_room_is_listed_for_later_dates(): void
    {
        $room = Room::factory()->create(['rm_number' => 'O1', 'status' => 'unavailable']);
        \App\Models\Reservation::factory()->create(['room_id' => $room->id, 'status' => 'checked_in', 'check_in_date' => $this->day(0), 'check_out_date' => $this->day(2)]);

        $this->get('/search-room?check_in=' . $this->day(4) . '&check_out=' . $this->day(6))
            ->assertViewHas('rooms', fn ($page) => $this->numbers($page) === ['O1']);
    }

    public function test_check_out_before_check_in_falls_back_to_tonight(): void
    {
        $this->get('/search-room?check_in=' . $this->day(5) . '&check_out=' . $this->day(3))
            ->assertViewHas('checkIn', $this->day(0));
    }

    public function test_bad_dates_are_explained(): void
    {
        $this->get('/search-room?check_in=' . $this->day(5) . '&check_out=' . $this->day(3))
            ->assertSee('The check out date must be after the check in date.');
    }

    public function test_card_reserve_carries_the_dates(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->get('/search-room?check_in=' . $this->day(4) . '&check_out=' . $this->day(6))
            ->assertSee('room_id=' . $room->id . '&amp;check_in=' . $this->day(4) . '&amp;check_out=' . $this->day(6), false);
    }

    public function test_no_available_rooms_says_so(): void
    {
        $this->get('/search-room')->assertSee('No rooms are available right now');
    }
}
