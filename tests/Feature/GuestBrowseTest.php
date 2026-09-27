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

    public function test_no_available_rooms_says_so(): void
    {
        $this->get('/search-room')->assertSee('No rooms are available right now');
    }
}
