<?php

namespace App\Support;

/**
 * Ticket checkout totals.
 *
 * The platform fee is a percentage of the discounted ticket amount and is
 * added to what the customer pays. It is not taken out of the organiser share.
 */
final class CheckoutPricing
{
    /**
     * @param  list<array{unit_price: float|int|string, quantity: int, discount_amount?: float|int|string}>  $lines
     * @return array{
     *     subtotal: string,
     *     discount_amount: string,
     *     platform_fee_amount: string,
     *     total_amount: string,
     *     lines: list<array{
     *         unit_price: string,
     *         quantity: int,
     *         discount_amount: string,
     *         total_price: string,
     *         platform_fee_rate: string,
     *         platform_share_amount: string,
     *         organiser_share_amount: string
     *     }>
     * }
     */
    public static function calculate(array $lines, float $feeRate): array
    {
        $feeRate = max(0, $feeRate);
        $pricedLines = [];
        $subtotal = 0.0;
        $discountTotal = 0.0;
        $platformFeeTotal = 0.0;

        foreach ($lines as $line) {
            $quantity = max(0, (int) $line['quantity']);
            $unitPrice = round(max(0, (float) $line['unit_price']), 2);
            $lineSubtotal = round($unitPrice * $quantity, 2);
            $lineDiscount = round(max(0, (float) ($line['discount_amount'] ?? 0)), 2);
            $lineDiscount = min($lineDiscount, $lineSubtotal);
            $discountedSubtotal = round($lineSubtotal - $lineDiscount, 2);
            $platformShare = round($discountedSubtotal * ($feeRate / 100), 2);

            $subtotal = round($subtotal + $lineSubtotal, 2);
            $discountTotal = round($discountTotal + $lineDiscount, 2);
            $platformFeeTotal = round($platformFeeTotal + $platformShare, 2);

            $pricedLines[] = [
                'unit_price' => self::money($unitPrice),
                'quantity' => $quantity,
                'discount_amount' => self::money($lineDiscount),
                'total_price' => self::money($lineSubtotal),
                'platform_fee_rate' => self::money($feeRate),
                'platform_share_amount' => self::money($platformShare),
                'organiser_share_amount' => self::money($discountedSubtotal),
            ];
        }

        return [
            'subtotal' => self::money($subtotal),
            'discount_amount' => self::money($discountTotal),
            'platform_fee_amount' => self::money($platformFeeTotal),
            'total_amount' => self::money(round($subtotal - $discountTotal + $platformFeeTotal, 2)),
            'lines' => $pricedLines,
        ];
    }

    private static function money(float $amount): string
    {
        return number_format(max(0, $amount), 2, '.', '');
    }
}
