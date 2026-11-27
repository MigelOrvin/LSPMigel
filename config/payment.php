<?php

return [
    'bank_name' => env(
        'PAYMENT_BANK_NAME',
        'Bank Demo'
    ),

    'account_number' => env(
        'PAYMENT_ACCOUNT_NUMBER',
        '1234567890'
    ),

    'account_holder' => env(
        'PAYMENT_ACCOUNT_HOLDER',
        'DentalCare'
    ),

    'demo' => env('PAYMENT_DEMO', true),
];