<?php

namespace App\Services;

class FinancialCalculator
{
    /**
     * Add two monetary values with proper precision
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public static function add(float $a, float $b): float
    {
        return round($a + $b, 2);
    }

    /**
     * Subtract two monetary values with proper precision
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public static function subtract(float $a, float $b): float
    {
        return round($a - $b, 2);
    }

    /**
     * Multiply monetary value by quantity with proper precision
     *
     * @param float $price
     * @param float $quantity
     * @return float
     */
    public static function multiply(float $price, float $quantity): float
    {
        return round($price * $quantity, 2);
    }

    /**
     * Divide monetary value with proper precision
     *
     * @param float $dividend
     * @param float $divisor
     * @return float
     */
    public static function divide(float $dividend, float $divisor): float
    {
        if ($divisor == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }
        return round($dividend / $divisor, 2);
    }

    /**
     * Calculate percentage with proper precision
     *
     * @param float $value
     * @param float $total
     * @return float
     */
    public static function percentage(float $value, float $total): float
    {
        if ($total == 0) {
            return 0.0;
        }
        return round(($value / $total) * 100, 2);
    }

    /**
     * Calculate tax amount with proper precision
     *
     * @param float $amount
     * @param float $taxRate
     * @param bool $inclusive
     * @return array
     */
    public static function calculateTax(float $amount, float $taxRate, bool $inclusive = false): array
    {
        $taxRateDecimal = $taxRate / 100;

        if ($inclusive) {
            // For inclusive tax: tax_amount = amount - (amount / (1 + tax_rate))
            $divisor = self::add(1, $taxRateDecimal);
            $netAmount = self::divide($amount, $divisor);
            $taxAmount = self::subtract($amount, $netAmount);
            $grossAmount = $amount;
        } else {
            // For exclusive tax: tax_amount = amount * tax_rate
            $taxAmount = self::multiply($amount, $taxRateDecimal);
            $netAmount = $amount;
            $grossAmount = self::add($amount, $taxAmount);
        }

        return [
            'net_amount' => self::roundToCent($netAmount),
            'tax_amount' => self::roundToCent($taxAmount),
            'gross_amount' => self::roundToCent($grossAmount),
            'tax_rate' => $taxRate,
            'inclusive' => $inclusive
        ];
    }

    /**
     * Calculate multiple taxes on an amount
     *
     * @param float $amount
     * @param array $taxes Array of ['rate' => float, 'inclusive' => bool, 'name' => string]
     * @return array
     */
    public static function calculateMultipleTaxes(float $amount, array $taxes): array
    {
        $result = [
            'original_amount' => $amount,
            'taxes' => [],
            'total_tax' => 0,
            'net_amount' => $amount,
            'gross_amount' => $amount
        ];

        foreach ($taxes as $tax) {
            $taxCalculation = self::calculateTax($amount, $tax['rate'], $tax['inclusive'] ?? false);

            $result['taxes'][] = [
                'name' => $tax['name'] ?? 'Tax',
                'rate' => $tax['rate'],
                'inclusive' => $tax['inclusive'] ?? false,
                'tax_amount' => $taxCalculation['tax_amount'],
                'net_amount' => $taxCalculation['net_amount'],
                'gross_amount' => $taxCalculation['gross_amount']
            ];

            $result['total_tax'] = self::add($result['total_tax'], $taxCalculation['tax_amount']);
        }

        $result['net_amount'] = self::subtract($amount, $result['total_tax']);
        $result['gross_amount'] = self::add($amount, $result['total_tax']);

        // Round final amounts
        $result['total_tax'] = self::roundToCent($result['total_tax']);
        $result['net_amount'] = self::roundToCent($result['net_amount']);
        $result['gross_amount'] = self::roundToCent($result['gross_amount']);

        return $result;
    }

    /**
     * Calculate tax for invoice items
     *
     * @param array $items Array of ['amount' => float, 'quantity' => int, 'tax_rate' => float, 'tax_inclusive' => bool]
     * @return array
     */
    public static function calculateInvoiceTax(array $items): array
    {
        $result = [
            'items' => [],
            'subtotal' => 0,
            'total_tax' => 0,
            'total_amount' => 0
        ];

        foreach ($items as $item) {
            $lineTotal = self::multiply($item['amount'], $item['quantity'] ?? 1);
            $taxCalculation = self::calculateTax($lineTotal, $item['tax_rate'] ?? 0, $item['tax_inclusive'] ?? false);

            $result['items'][] = [
                'amount' => $item['amount'],
                'quantity' => $item['quantity'] ?? 1,
                'line_total' => self::roundToCent($lineTotal),
                'tax_rate' => $item['tax_rate'] ?? 0,
                'tax_inclusive' => $item['tax_inclusive'] ?? false,
                'tax_amount' => $taxCalculation['tax_amount'],
                'net_amount' => $taxCalculation['net_amount'],
                'gross_amount' => $taxCalculation['gross_amount']
            ];

            $result['subtotal'] = self::add($result['subtotal'], $lineTotal);
            $result['total_tax'] = self::add($result['total_tax'], $taxCalculation['tax_amount']);
        }

        $result['total_amount'] = self::add($result['subtotal'], $result['total_tax']);

        // Round final amounts
        $result['subtotal'] = self::roundToCent($result['subtotal']);
        $result['total_tax'] = self::roundToCent($result['total_tax']);
        $result['total_amount'] = self::roundToCent($result['total_amount']);

        return $result;
    }

    /**
     * Calculate compound interest with proper precision
     *
     * @param float $principal
     * @param float $rate
     * @param int $periods
     * @param int $compoundsPerPeriod
     * @return float
     */
    public static function compoundInterest(float $principal, float $rate, int $periods, int $compoundsPerPeriod = 1): float
    {
        $rateDecimal = $rate / 100;
        $effectiveRate = $rateDecimal / $compoundsPerPeriod;
        $totalPeriods = $periods * $compoundsPerPeriod;

        $amount = $principal * pow(1 + $effectiveRate, $totalPeriods);
        return round($amount, 2);
    }

    /**
     * Format monetary value for display
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public static function formatMoney(float $amount, string $currency = 'IDR'): string
    {
        return $currency . ' ' . number_format($amount, 2, ',', '.');
    }

    /**
     * Validate monetary amount
     *
     * @param mixed $amount
     * @return bool
     */
    public static function isValidAmount($amount): bool
    {
        return is_numeric($amount) && $amount >= 0 && $amount <= 999999999.99;
    }

    /**
     * Round to nearest cent (0.01)
     *
     * @param float $amount
     * @return float
     */
    public static function roundToCent(float $amount): float
    {
        return round($amount, 2);
    }

    /**
     * Calculate margin/profit percentage
     *
     * @param float $sellingPrice
     * @param float $costPrice
     * @return float
     */
    public static function calculateMargin(float $sellingPrice, float $costPrice): float
    {
        if ($costPrice == 0) {
            return 0.0;
        }
        return self::percentage(self::subtract($sellingPrice, $costPrice), $costPrice);
    }
}