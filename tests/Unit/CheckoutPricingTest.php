<?php

use App\Support\CheckoutPricing;

test('platform fee is added to a discounted ticket instead of reducing the organiser share', function () {
    $pricing = CheckoutPricing::calculate([
        [
            'unit_price' => 90,
            'quantity' => 1,
            'discount_amount' => 10,
        ],
    ], 5);

    expect($pricing['subtotal'])->toBe('90.00')
        ->and($pricing['discount_amount'])->toBe('10.00')
        ->and($pricing['platform_fee_amount'])->toBe('4.00')
        ->and($pricing['total_amount'])->toBe('84.00')
        ->and($pricing['lines'][0]['total_price'])->toBe('90.00')
        ->and($pricing['lines'][0]['discount_amount'])->toBe('10.00')
        ->and($pricing['lines'][0]['platform_share_amount'])->toBe('4.00')
        ->and($pricing['lines'][0]['organiser_share_amount'])->toBe('80.00')
        ->and($pricing['lines'][0]['platform_fee_rate'])->toBe('5.00');
});

test('platform fee is calculated once across quantities and lines', function () {
    $pricing = CheckoutPricing::calculate([
        [
            'unit_price' => 40,
            'quantity' => 2,
            'discount_amount' => 0,
        ],
        [
            'unit_price' => 10,
            'quantity' => 1,
            'discount_amount' => 5,
        ],
    ], 5);

    expect($pricing['subtotal'])->toBe('90.00')
        ->and($pricing['discount_amount'])->toBe('5.00')
        ->and($pricing['platform_fee_amount'])->toBe('4.25')
        ->and($pricing['total_amount'])->toBe('89.25')
        ->and($pricing['lines'][0]['organiser_share_amount'])->toBe('80.00')
        ->and($pricing['lines'][0]['platform_share_amount'])->toBe('4.00')
        ->and($pricing['lines'][1]['organiser_share_amount'])->toBe('5.00')
        ->and($pricing['lines'][1]['platform_share_amount'])->toBe('0.25');
});

test('discount cannot exceed the ticket line and a missing fee adds nothing', function () {
    $pricing = CheckoutPricing::calculate([
        [
            'unit_price' => 20,
            'quantity' => 1,
            'discount_amount' => 50,
        ],
    ], 0);

    expect($pricing['subtotal'])->toBe('20.00')
        ->and($pricing['discount_amount'])->toBe('20.00')
        ->and($pricing['platform_fee_amount'])->toBe('0.00')
        ->and($pricing['total_amount'])->toBe('0.00')
        ->and($pricing['lines'][0]['organiser_share_amount'])->toBe('0.00');
});
