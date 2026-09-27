<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GuestRoomDetailTest extends TestCase
{
    use RefreshDatabase;

    private function visit(Room $room)
    {
        return $this->get('/guest/rooms/details/' . $room->id);
    }

    public function test_available_room_offers_reserve(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->visit($room)->assertSee(url('/guest/rooms/reserve') . '?room_id=' . $room->id);
    }

    public function test_occupied_room_still_offers_reserve(): void
    {
        $room = Room::factory()->create(['status' => 'unavailable']);

        $this->visit($room)->assertSee(url('/guest/rooms/reserve') . '?room_id=' . $room->id);
    }

    public function test_reserve_carries_the_dates(): void
    {
        $room = Room::factory()->create(['status' => 'available']);
        $in = today()->addDays(4)->toDateString();
        $out = today()->addDays(6)->toDateString();

        $this->get('/guest/rooms/details/' . $room->id . '?check_in=' . $in . '&check_out=' . $out)
            ->assertSee('room_id=' . $room->id . '&amp;check_in=' . $in . '&amp;check_out=' . $out, false);
    }

    public function test_overlapping_dates_are_explained(): void
    {
        $room = Room::factory()->create(['status' => 'available']);
        \App\Models\Reservation::factory()->create(['room_id' => $room->id, 'status' => 'confirmed', 'check_in_date' => today()->addDays(3)->toDateString(), 'check_out_date' => today()->addDays(5)->toDateString()]);

        $this->get('/guest/rooms/details/' . $room->id . '?check_in=' . today()->addDays(4)->toDateString() . '&check_out=' . today()->addDays(6)->toDateString())
            ->assertSee('This room is already booked for some of these dates.');
    }

    public function test_bad_dates_are_explained(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $this->get('/guest/rooms/details/' . $room->id . '?check_in=' . today()->addDays(5)->toDateString() . '&check_out=' . today()->addDays(3)->toDateString())
            ->assertSee('The check out date must be after the check in date.');
    }

    public function test_room_in_maintenance_explains_it_cannot_be_booked(): void
    {
        $room = Room::factory()->create(['status' => 'maintenance']);

        $this->visit($room)->assertSee('This room is not available to book right now.');
    }

    public function test_room_in_maintenance_has_no_reserve_link(): void
    {
        $room = Room::factory()->create(['status' => 'maintenance']);

        $this->visit($room)->assertDontSee('?room_id=' . $room->id);
    }

    public function test_every_photo_is_in_the_gallery(): void
    {
        $room = Room::factory()->create();
        foreach (['a.jpg', 'b.jpg'] as $path) {
            DB::table('rooms_photos')->insert(['room_id' => $room->id, 'photo_path' => 'room-photos/' . $path]);
        }

        $this->visit($room)->assertSee('photo 2 of 2');
    }

    public function test_room_without_photos_says_so(): void
    {
        $this->visit(Room::factory()->create())->assertSee('No photos yet');
    }

    public function test_guest_page_has_no_staff_photo_link(): void
    {
        $room = Room::factory()->create();

        $this->visit($room)->assertDontSee(url('/rooms/edit/' . $room->id));
    }

    public function test_back_returns_to_the_room_browser(): void
    {
        $this->visit(Room::factory()->create())->assertSee('href="' . url('/search-room') . '"', false);
    }
}
