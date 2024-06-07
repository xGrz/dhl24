<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCourierBooking;

class DHLCourierBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_booking_throws_errors_when_different_dates_provided()
    {
        $this->expectException(DHL24Exception::class);
        $this->expectExceptionCode(1001);

        DHL24::booking()->add(now()->subDays(1), now(), 'SomeInfo');
    }

    public function test_add_booking_throws_errors_when_from_is_later_then_to()
    {
        $this->expectException(DHL24Exception::class);
        $this->expectExceptionCode(1002);

        DHL24::booking()->add(now()->addSecond(), now(), 'SomeInfo');
    }

    public function test_add_booking_throws_error_when_pickup_dates_window_is_less_then_2h()
    {
        $this->expectException(DHL24Exception::class);
        $this->expectExceptionCode(1003);

        DHL24::booking()->add(now(), now()->addHours(2)->subSecond(), 'SomeInfo');
    }

    public function test_add_booking_with_pickup_window_grater_then_2h()
    {
        $from = now();
        $to = now()->copy()->addHours(2);
        DHL24::booking()->add($from, $to, 'SomeInfo');

        $this->assertDatabaseHas(DHLCourierBooking::class, [
            'pickup_from' => $from->milliseconds(0),
            'pickup_to' => $to,
            'additional_info' => 'SomeInfo',
            'order_id' => null
        ]);
    }

}
