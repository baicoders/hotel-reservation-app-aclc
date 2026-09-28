<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSmokeTest extends TestCase
{
    use RefreshDatabase;

    private Reservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservation = Reservation::factory()->create(['status' => 'confirmed']);
        Payment::factory()->create(['reservation_id' => $this->reservation->id]);
        Room::whereKey($this->reservation->room_id)->update(['status' => 'available']);
    }

    public function test_every_public_page_renders(): void
    {
        $room = $this->reservation->room_id;
        $failures = collect(['/', '/home', '/login', '/search-room', '/guest/rooms/details/' . $room, '/guest/rooms/reserve?room_id=' . $room, '/guest/rooms/reserve?room_id[]=' . $room, '/guest/receipt', '/no-such-page'])
            ->filter(fn ($url) => $this->get($url)->getStatusCode() >= 500)
            ->values()
            ->all();

        $this->assertSame([], $failures);
    }

    public function test_every_staff_page_renders(): void
    {
        $this->actingAs(User::factory()->create());
        $r = $this->reservation;
        $failures = collect(['/dashboard', '/reservations', '/reservations/details/' . $r->id, '/guest', '/guest/details/' . $r->guest_id, '/rooms', '/rooms/search?query=1', '/rooms/create', '/rooms/details/' . $r->room_id, '/rooms/edit/' . $r->room_id])
            ->filter(fn ($url) => $this->get($url)->getStatusCode() !== 200)
            ->values()
            ->all();

        $this->assertSame([], $failures);
    }
}
