<?php

use Tests\TestCase;
use xGrz\Dhl24\Exceptions\MoneyValidationException;
use xGrz\Dhl24\Helpers\Money;

class MoneyTest extends TestCase
{

    public function test_amount(): void
    {
        $money = Money::from('123');

        $this->assertEquals(12300, $money->getAmount());
    }

    public function test_amount_with_comma_separated_cents(): void
    {
        $money = Money::from('123,12');

        $this->assertEquals(12312, $money->getAmount());
    }

    public function test_amount_with_dot_separated_cents(): void
    {
        $money = Money::from('123.12');

        $this->assertEquals(12312, $money->getAmount());
    }

    public function test_amount_with_space_separated_amount(): void
    {
        $money = Money::from('1 123.12');

        $this->assertEquals(112312, $money->getAmount());
    }

    public function test_amount_with_to_many_cents_is_cut(): void
    {
        $money = Money::from('200,123');

        $this->assertEquals(20012, $money->getAmount());
    }

    public function test_amount_default_formatting(): void
    {
        $money = Money::from('200,123');
        $this->assertEquals('200,12', $money->format());
    }

    public function test_amount_formatting_with_dot_separated_cents(): void
    {
        $money = Money::from('200,123');
        $this->assertEquals('200.12', $money->format('.'));
        $this->assertEquals(200.12, $money->format('.'));
    }

    public function test_amount_formatting_with_thousands_space_separated(): void
    {
        $money = Money::from('2992200,12');
        $this->assertEquals('2 992 200,12', $money->format(thousandsSeparator: ' '));
    }

    public function test_formatting_to_number(): void
    {
        $money = Money::from('200,123');
        $this->assertEquals(200.12, $money->toNumber());
    }

    public function test_amount_multiply(): void
    {
        $money = Money::from('200,12');
        $money = $money->multiply(2);

        $this->assertEquals(40024, $money->getAmount());
    }

    public function test_add_percent_to_amount(): void
    {
        $money = Money::from('36,59');
        $money = $money->addPercent(23);

        $money2 = Money::from('36,58');
        $money2 = $money2->addPercent(23);

        $this->assertEquals(4501, $money->getAmount());
        $this->assertEquals(4499, $money2->getAmount());
    }

    public function test_subtract_percent(): void
    {
        $money = Money::from('45');
        $money = $money->subtractPercent(23);

        $this->assertEquals(3658, $money->getAmount());
    }

    public function test_validation_success_with_comma(): void
    {
        $money = Money::isValid('12,34');

        $this->assertTrue($money);
    }

    public function test_validation_success_with_dot(): void
    {
        $money = Money::isValid('12.34');

        $this->assertTrue($money);
    }
    public function test_validation_fails_with_random_space_separator(): void
    {
        $money = Money::isValid('200 20002 2000');

        $this->assertTrue($money);
    }

    public function test_validation_fails_when_not_numeric(): void
    {
        $money = Money::isValid('1234a');

        $this->assertFalse($money);
    }

    public function test_make_throws_exception_when_not_numeric(): void
    {
        $this->expectException(MoneyValidationException::class);

        Money::from('1234a');
    }

}
