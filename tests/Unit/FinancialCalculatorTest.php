<?php

namespace Tests\Unit;

use App\Services\FinancialCalculator;
use PHPUnit\Framework\TestCase;

class FinancialCalculatorTest extends TestCase
{
    public function test_add_two_positive_numbers()
    {
        $result = FinancialCalculator::add(10.50, 5.25);
        $this->assertEquals(15.75, $result);
    }

    public function test_add_with_precision()
    {
        $result = FinancialCalculator::add(10.123, 5.456);
        $this->assertEquals(15.58, $result); // Should round to 2 decimal places
    }

    public function test_subtract_two_numbers()
    {
        $result = FinancialCalculator::subtract(20.00, 5.50);
        $this->assertEquals(14.50, $result);
    }

    public function test_multiply_price_and_quantity()
    {
        $result = FinancialCalculator::multiply(10.50, 3);
        $this->assertEquals(31.50, $result);
    }

    public function test_divide_two_numbers()
    {
        $result = FinancialCalculator::divide(100.00, 4.00);
        $this->assertEquals(25.00, $result);
    }

    public function test_divide_by_zero_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        FinancialCalculator::divide(100.00, 0);
    }

    public function test_percentage_calculation()
    {
        $result = FinancialCalculator::percentage(25.00, 100.00);
        $this->assertEquals(25.00, $result);
    }

    public function test_percentage_with_zero_total()
    {
        $result = FinancialCalculator::percentage(25.00, 0);
        $this->assertEquals(0.00, $result);
    }

    public function test_tax_calculation_exclusive()
    {
        $result = FinancialCalculator::calculateTax(100.00, 10.00, false);

        $this->assertEquals(100.00, $result['net_amount']);
        $this->assertEquals(10.00, $result['tax_amount']);
        $this->assertEquals(110.00, $result['gross_amount']);
        $this->assertEquals(10.00, $result['tax_rate']);
    }

    public function test_tax_calculation_inclusive()
    {
        $result = FinancialCalculator::calculateTax(110.00, 10.00, true);

        $this->assertEquals(100.00, $result['net_amount']);
        $this->assertEquals(10.00, $result['tax_amount']);
        $this->assertEquals(110.00, $result['gross_amount']);
        $this->assertEquals(10.00, $result['tax_rate']);
    }

    public function test_compound_interest()
    {
        $result = FinancialCalculator::compoundInterest(1000.00, 5.00, 1, 1);
        $this->assertEquals(1050.00, $result);
    }

    public function test_format_money()
    {
        $result = FinancialCalculator::formatMoney(1234.56, 'IDR');
        $this->assertEquals('IDR 1.234,56', $result);
    }

    public function test_is_valid_amount()
    {
        $this->assertTrue(FinancialCalculator::isValidAmount(100.50));
        $this->assertTrue(FinancialCalculator::isValidAmount(0.00));
        $this->assertFalse(FinancialCalculator::isValidAmount(-10.00));
        $this->assertFalse(FinancialCalculator::isValidAmount('invalid'));
    }

    public function test_round_to_cent()
    {
        $result = FinancialCalculator::roundToCent(10.123);
        $this->assertEquals(10.12, $result);
    }

    public function test_calculate_margin()
    {
        $result = FinancialCalculator::calculateMargin(120.00, 100.00);
        $this->assertEquals(20.00, $result);
    }

    public function test_calculate_margin_with_zero_cost()
    {
        $result = FinancialCalculator::calculateMargin(120.00, 0);
        $this->assertEquals(0.00, $result);
    }
}