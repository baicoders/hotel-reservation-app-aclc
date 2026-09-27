<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestListTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create();
    }

    private function ids($page): array
    {
        return collect($page->items())->pluck('id')->all();
    }

    public function test_list_shows_the_guest_address(): void
    {
        Guest::factory()->create(['address' => '12 Rizal Street, Ormoc']);

        $this->actingAs($this->staff())->get('/guest')->assertSee('12 Rizal Street, Ormoc');
    }

    public function test_oldest_of_twenty_one_is_on_page_two(): void
    {
        foreach (range(1, 21) as $days) {
            $last = Guest::factory()->create(['created_at' => now()->subDays($days)]);
        }

        $this->actingAs($this->staff())
            ->get('/guest?page=2')
            ->assertViewHas('guests', fn ($page) => $this->ids($page) === [$last->id]);
    }

    public function test_search_matches_email(): void
    {
        $match = Guest::factory()->create(['email' => 'lena.cruz@example.com']);
        Guest::factory()->create(['email' => 'other@example.com']);

        $this->actingAs($this->staff())
            ->get('/guest?query=lena')
            ->assertViewHas('guests', fn ($page) => $this->ids($page) === [$match->id]);
    }

    public function test_search_keeps_the_typed_query(): void
    {
        $this->actingAs($this->staff())->get('/guest?query=lena')->assertSee('value="lena"', false);
    }

    public function test_delete_warning_states_the_reservation_count(): void
    {
        $guest = Guest::factory()->create();
        Reservation::factory()->count(2)->create(['guest_id' => $guest->id]);

        $this->actingAs($this->staff())->get('/guest')->assertSee('their 2 reservations');
    }

    public function test_delete_releases_the_room_of_an_active_reservation(): void
    {
        $room = Room::factory()->create(['rm_number' => '305', 'status' => 'unavailable']);
        $guest = Guest::factory()->create();
        Reservation::factory()->create(['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'confirmed']);

        $this->actingAs($this->staff())->delete('/guest/delete/' . $guest->id);

        $this->assertSame('available', $room->fresh()->status);
    }

    public function test_delete_keeps_a_room_another_guest_holds(): void
    {
        $room = Room::factory()->create(['status' => 'unavailable']);
        $guest = Guest::factory()->create();
        Reservation::factory()->create(['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'confirmed']);
        Reservation::factory()->create(['room_id' => $room->id, 'status' => 'checked_in']);

        $this->actingAs($this->staff())->delete('/guest/delete/' . $guest->id);

        $this->assertSame('unavailable', $room->fresh()->status);
    }

    public function test_delete_leaves_a_room_in_maintenance_alone(): void
    {
        $room = Room::factory()->create(['status' => 'maintenance']);
        $guest = Guest::factory()->create();
        Reservation::factory()->create(['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'confirmed']);

        $this->actingAs($this->staff())->delete('/guest/delete/' . $guest->id);

        $this->assertSame('maintenance', $room->fresh()->status);
    }

    public function test_delete_returns_to_the_list_with_a_notice(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->staff())
            ->delete('/guest/delete/' . $guest->id)
            ->assertSessionHas('success', 'Guest and related reservations deleted successfully.');
    }
}
