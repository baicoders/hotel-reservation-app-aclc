<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptTest extends TestCase
{
    use RefreshDatabase;

    private function book()
    {
        $room = Room::factory()->create(['rm_number' => 'R-88', 'rm_price_per_night' => 100, 'status' => 'available']);

        return $this->post('/guest/rooms/booking-room', [
            'room_id' => $room->id,
            'check_in_date' => today()->addDays(10)->toDateString(),
            'check_out_date' => today()->addDays(12)->toDateString(),
            'name' => 'Lena Cruz',
            'email' => 'lena.cruz@example.com',
            'phone' => '0917 555 0100',
            'address' => 'Ormoc City',
            'amount' => '200.00',
            'payment_method' => 'paypal',
            'payment_date' => today()->toDateString(),
        ]);
    }

    public function test_booking_redirects_to_the_receipt(): void
    {
        $this->book()->assertRedirect('/guest/receipt');
    }

    public function test_receipt_shows_the_confirmation_number(): void
    {
        $this->book();
        $number = '#' . str_pad((string) Reservation::first()->id, 6, '0', STR_PAD_LEFT);

        $this->get('/guest/receipt')->assertSee($number);
    }

    public function test_receipt_shows_the_total_paid(): void
    {
        $this->book();

        $this->get('/guest/receipt')->assertSee('$200.00');
    }

    public function test_receipt_shows_the_payment_method_label(): void
    {
        $this->book();

        $this->get('/guest/receipt')->assertSee('PayPal');
    }

    public function test_reloading_the_receipt_books_nothing_more(): void
    {
        $this->book();
        $this->get('/guest/receipt');
        $this->get('/guest/receipt');

        $this->assertSame(1, Reservation::count());
    }

    public function test_receipt_without_a_booking_goes_to_the_room_browser(): void
    {
        $this->get('/guest/receipt')->assertRedirect('/search-room');
    }

    public function test_receipt_does_not_claim_an_email_was_sent(): void
    {
        $this->book();

        $this->get('/guest/receipt')->assertDontSee('email has been sent');
    }

    public function test_receipt_expires_after_fifteen_minutes(): void
    {
        $this->book();
        $this->travel(16)->minutes();

        $this->get('/guest/receipt')->assertRedirect('/search-room');
    }

    public function test_done_clears_the_receipt(): void
    {
        $this->book();
        $this->post('/guest/receipt/clear', ['next' => 'home'])->assertRedirect('/home');

        $this->get('/guest/receipt')->assertRedirect('/search-room');
    }

    public function test_staff_sign_in_clears_the_receipt(): void
    {
        $this->book();
        \App\Models\User::factory()->create(['name' => 'Desk', 'password' => 'desk']);
        $this->post('/login', ['username' => 'Desk', 'password' => 'desk']);
        auth()->logout();

        $this->get('/guest/receipt')->assertRedirect('/search-room');
    }

    public function test_receipt_offers_another_booking(): void
    {
        $this->book();

        $this->get('/guest/receipt')->assertSee('Book another room');
    }
}
