<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomFormTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create();
    }

    public function test_whole_number_price_creates_the_room(): void
    {
        $this->actingAs($this->staff())
            ->post('/rooms/record', ['roomNumber' => '305', 'roomType' => 'Double', 'roomPrice' => '100', 'status' => 'available'])
            ->assertRedirect('/rooms');

        $this->assertDatabaseHas('rooms', ['rm_number' => '305', 'rm_price_per_night' => 100]);
    }

    public function test_duplicate_room_number_is_rejected(): void
    {
        Room::factory()->create(['rm_number' => '305']);

        $this->actingAs($this->staff())
            ->from('/rooms/create')
            ->post('/rooms/record', ['roomNumber' => '305', 'roomType' => 'Double', 'roomPrice' => '100'])
            ->assertSessionHasErrors('roomNumber');
    }

    public function test_room_number_error_is_shown_under_the_field(): void
    {
        $this->actingAs($this->staff())
            ->withSession(['errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['roomNumber' => 'The room number has already been taken.']))])
            ->get('/rooms/create')
            ->assertSee('id="roomNumber-error"', false);
    }

    public function test_two_photos_are_stored_on_create(): void
    {
        Storage::fake('public');

        $this->actingAs($this->staff())->post('/rooms/record', [
            'roomNumber' => '306', 'roomType' => 'Suite', 'roomPrice' => '250.50',
            'roomPhotos' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
        ]);

        $this->assertSame(2, DB::table('rooms_photos')->count());
    }

    public function test_invalid_photo_does_not_create_the_room(): void
    {
        Storage::fake('public');

        $this->actingAs($this->staff())->post('/rooms/record', [
            'roomNumber' => '307', 'roomType' => 'Suite', 'roomPrice' => '250.00',
            'roomPhotos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')],
        ]);

        $this->assertDatabaseMissing('rooms', ['rm_number' => '307']);
    }

    public function test_stored_photo_extension_comes_from_the_file_content(): void
    {
        Storage::fake('public');
        $room = Room::factory()->create();
        $image = UploadedFile::fake()->image('x.png');
        $disguised = new UploadedFile($image->getPathname(), 'x.html', 'image/png', null, true);

        $this->actingAs($this->staff())->post('/rooms/update/' . $room->id, [
            'roomType' => 'Double', 'roomPrice' => '120.00', 'status' => 'available',
            'roomPhotos' => [$disguised],
        ]);

        $this->assertStringEndsWith('.png', DB::table('rooms_photos')->where('room_id', $room->id)->value('photo_path'));
    }

    public function test_deleting_a_room_removes_its_photo_files(): void
    {
        Storage::fake('public');
        $this->actingAs($this->staff())->post('/rooms/record', [
            'roomNumber' => '308', 'roomType' => 'Suite', 'roomPrice' => '250',
            'roomPhotos' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
        ]);
        $room = Room::where('rm_number', '308')->first();
        $paths = DB::table('rooms_photos')->where('room_id', $room->id)->pluck('photo_path');

        $this->actingAs($this->staff())->delete('/rooms/delete/' . $room->id);

        $this->assertSame([], array_values(array_filter($paths->all(), fn ($path) => Storage::disk('public')->exists($path))));
    }

    public function test_whole_number_price_updates_the_room(): void
    {
        $room = Room::factory()->create(['rm_price_per_night' => 90]);

        $this->actingAs($this->staff())
            ->post('/rooms/update/' . $room->id, ['roomType' => 'Double', 'roomPrice' => '120', 'status' => 'available']);

        $this->assertSame('120.00', $room->fresh()->rm_price_per_night);
    }

    public function test_update_redirects_to_the_room_info_page(): void
    {
        $room = Room::factory()->create();

        $this->actingAs($this->staff())
            ->post('/rooms/update/' . $room->id, ['roomType' => 'Double', 'roomPrice' => '120.00', 'status' => 'available'])
            ->assertRedirect('/rooms/details/' . $room->id);
    }

    public function test_photo_can_be_added_when_editing(): void
    {
        Storage::fake('public');
        $room = Room::factory()->create();

        $this->actingAs($this->staff())->post('/rooms/update/' . $room->id, [
            'roomType' => 'Double', 'roomPrice' => '120.00', 'status' => 'available',
            'roomPhotos' => [UploadedFile::fake()->image('c.jpg')],
        ]);

        $this->assertSame(1, DB::table('rooms_photos')->where('room_id', $room->id)->count());
    }
}
