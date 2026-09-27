<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomListTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create();
    }

    private function roomNumbers($rooms): array
    {
        return collect($rooms->items())->pluck('rm_number')->all();
    }

    public function test_first_page_shows_eleven_rooms(): void
    {
        Room::factory()->count(11)->create();

        $this->actingAs($this->staff())
            ->get('/rooms')
            ->assertViewHas('rooms', fn ($rooms) => count($rooms->items()) === 11);
    }

    public function test_thirteenth_room_is_on_page_two(): void
    {
        foreach (range(1, 13) as $n) {
            Room::factory()->create(['rm_number' => sprintf('A%02d', $n)]);
        }

        $this->actingAs($this->staff())
            ->get('/rooms?page=2')
            ->assertViewHas('rooms', fn ($rooms) => $this->roomNumbers($rooms) === ['A13']);
    }

    public function test_rooms_are_ordered_by_number_naturally(): void
    {
        Room::factory()->create(['rm_number' => '100']);
        Room::factory()->create(['rm_number' => '99']);

        $this->actingAs($this->staff())
            ->get('/rooms')
            ->assertViewHas('rooms', fn ($rooms) => $this->roomNumbers($rooms) === ['99', '100']);
    }

    public function test_array_query_does_not_break_the_page(): void
    {
        $this->actingAs($this->staff())
            ->get('/rooms?query[]=x')
            ->assertOk();
    }

    public function test_underscore_in_search_is_literal(): void
    {
        Room::factory()->create(['rm_number' => '101']);

        $this->actingAs($this->staff())
            ->get('/rooms?query=_')
            ->assertViewHas('rooms', fn ($rooms) => $rooms->total() === 0);
    }

    public function test_search_keeps_the_typed_query(): void
    {
        $this->actingAs($this->staff())
            ->get('/rooms/search?query=Suite')
            ->assertSee('value="Suite"', false);
    }

    public function test_search_matches_room_type(): void
    {
        Room::factory()->create(['rm_number' => 'S-1', 'rm_type' => 'Suite']);
        Room::factory()->create(['rm_number' => 'D-1', 'rm_type' => 'Double']);

        $this->actingAs($this->staff())
            ->get('/rooms/search?query=Suite')
            ->assertViewHas('rooms', fn ($rooms) => $this->roomNumbers($rooms) === ['S-1']);
    }

    public function test_status_filter_lists_only_that_status(): void
    {
        Room::factory()->create(['rm_number' => 'M-1', 'status' => 'maintenance']);
        Room::factory()->create(['rm_number' => 'V-1', 'status' => 'available']);

        $this->actingAs($this->staff())
            ->get('/rooms?status=maintenance')
            ->assertViewHas('rooms', fn ($rooms) => $this->roomNumbers($rooms) === ['M-1']);
    }

    public function test_status_filter_combines_with_search(): void
    {
        Room::factory()->create(['rm_number' => 'M-1', 'rm_type' => 'Suite', 'status' => 'maintenance']);
        Room::factory()->create(['rm_number' => 'M-2', 'rm_type' => 'Double', 'status' => 'maintenance']);
        Room::factory()->create(['rm_number' => 'V-1', 'rm_type' => 'Suite', 'status' => 'available']);

        $this->actingAs($this->staff())
            ->get('/rooms?query=Suite&status=maintenance')
            ->assertViewHas('rooms', fn ($rooms) => $this->roomNumbers($rooms) === ['M-1']);
    }

    public function test_room_without_photo_shows_placeholder(): void
    {
        Room::factory()->create();

        $this->actingAs($this->staff())
            ->get('/rooms')
            ->assertSee('No photo yet');
    }

    public function test_staff_card_links_to_edit(): void
    {
        $room = Room::factory()->create();

        $this->actingAs($this->staff())->get('/rooms')->assertSee(url('/rooms/edit/' . $room->id));
    }

    public function test_staff_card_has_a_delete_form(): void
    {
        $room = Room::factory()->create();

        $this->actingAs($this->staff())->get('/rooms')->assertSee('action="' . url('/rooms/delete/' . $room->id) . '"', false);
    }

    public function test_staff_card_delete_states_the_reservation_count(): void
    {
        $room = Room::factory()->create();
        \App\Models\Reservation::factory()->count(2)->create(['room_id' => $room->id]);

        $this->actingAs($this->staff())->get('/rooms')->assertSee('its 2 reservations');
    }

    public function test_guest_cards_have_no_delete_form(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->get('/search-room')->assertDontSee(url('/rooms/delete/' . $room->id));
    }

    public function test_guest_search_links_to_room_details(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->get('/search-room')
            ->assertSee(url('/guest/rooms/details/' . $room->id));
    }
}
