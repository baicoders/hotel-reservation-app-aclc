<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class BookingFormTest extends TestCase
{
    use RefreshDatabase;

    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        $this->room = Room::factory()->create(['rm_price_per_night' => 100, 'status' => 'available']);
    }

    private function url(): string
    {
        return '/guest/rooms/reserve?room_id=' . $this->room->id . '&check_in=' . today()->addDays(4)->toDateString() . '&check_out=' . today()->addDays(7)->toDateString();
    }

    public function test_total_follows_the_dates(): void
    {
        $this->get($this->url())->assertViewHas('total', 300.0);
    }

    public function test_total_follows_the_kept_dates_after_an_error(): void
    {
        $this->withSession(['_old_input' => ['check_in_date' => today()->addDays(4)->toDateString(), 'check_out_date' => today()->addDays(9)->toDateString()]])
            ->get($this->url())
            ->assertViewHas('total', 500.0);
    }

    public function test_amount_field_holds_the_total(): void
    {
        $this->get($this->url())->assertSee('value="300.00"', false);
    }

    public function test_payment_method_is_kept_after_an_error(): void
    {
        $this->withSession(['_old_input' => ['payment_method' => 'paypal']])
            ->get($this->url())
            ->assertSee('value="paypal" selected', false);
    }

    public function test_array_old_input_does_not_break_the_form(): void
    {
        $this->withSession(['_old_input' => ['name' => ['x'], 'payment_method' => ['cash']]])
            ->get($this->url())
            ->assertOk();
    }

    public function test_reversed_kept_dates_show_the_dates_used_for_the_total(): void
    {
        $this->withSession(['_old_input' => ['check_in_date' => today()->addDays(6)->toDateString(), 'check_out_date' => today()->addDays(4)->toDateString()]])
            ->get($this->url())
            ->assertSee('name="check_in_date" value="' . today()->toDateString() . '"', false);
    }

    public function test_errors_sit_under_their_field(): void
    {
        $this->withSession(['errors' => (new ViewErrorBag)->put('default', new MessageBag(['email' => 'The email field must be a valid email address.']))])
            ->get($this->url())
            ->assertSee('id="email-error"', false);
    }

    public function test_errors_are_not_repeated_in_the_corner(): void
    {
        $this->withSession(['errors' => (new ViewErrorBag)->put('default', new MessageBag(['email' => 'The email field must be a valid email address.']))])
            ->get($this->url())
            ->assertDontSee('Please fix the following');
    }

    public function test_cancel_returns_to_the_room_with_the_dates(): void
    {
        $this->get($this->url())->assertSee(url('/guest/rooms/details/' . $this->room->id) . '?check_in=', false);
    }
}
