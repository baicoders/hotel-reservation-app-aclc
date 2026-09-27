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
