<?php

/*
|--------------------------------------------------------------------------
| Product Catalog — Server-side source of truth for prices
|--------------------------------------------------------------------------
| Prices are stored in integer euro cents. The frontend must NEVER be trusted
| for pricing — the server looks up products by code and calculates totals.
|
| product_code        => internal identifier sent from the cart
| title               => snapshot stored on order_items
| price_cents         => price in euro cents (integer)
| requires_shipping   => true for physical book, false for services
| payable             => false for "price on request" items (group_training_matthew)
*/

return [

    'consultation_single' => [
        'title' => [
            'lt' => 'Individuali konsultacija',
            'en' => 'Individual consultation',
            'ru' => 'Индивидуальная консультация',
        ],
        'price_cents' => 13200,
        'requires_shipping' => false,
        'payable' => true,
    ],

    'phobia_program_4w' => [
        'title' => [
            'lt' => '4 savaičių darbas su baime ar fobija',
            'en' => '4-week work with fear or phobia',
            'ru' => '4-недельная работа со страхом или фобией',
        ],
        'price_cents' => 60000,
        'requires_shipping' => false,
        'payable' => true,
    ],

    'smoking_cessation' => [
        'title' => [
            'lt' => 'Metimo rūkyti konsultacija',
            'en' => 'Smoking cessation consultation',
            'ru' => 'Консультация по отказу от курения',
        ],
        'price_cents' => 52000,
        'requires_shipping' => false,
        'payable' => true,
    ],

    'book_science_change' => [
        'title' => [
            'lt' => 'Knyga „Mokslu grįstas pokytis"',
            'en' => 'Book "Science-Based Change"',
            'ru' => 'Книга «Научно обоснованные перемены»',
        ],
        'price_cents' => 3000,
        'requires_shipping' => true,
        'payable' => true,
    ],

    'group_training_matthew' => [
        'title' => [
            'lt' => 'Mokymai grupėms su Matthew Cahill',
            'en' => 'Group training with Matthew Cahill',
            'ru' => 'Обучение для групп с Мэттью Кахиллом',
        ],
        'price_cents' => null,
        'requires_shipping' => false,
        'payable' => false,
    ],

];
