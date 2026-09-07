<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'consultation_single',
                'badge' => [
                    'lt' => 'Pirmas žingsnis',
                    'en' => 'First step',
                    'ru' => 'Первый шаг',
                ],
                'title' => [
                    'lt' => 'Individuali konsultacija',
                    'en' => 'Individual consultation',
                    'ru' => 'Индивидуальная консультация',
                ],
                'description' => [
                    'lt' => 'Viena individuali sesija konkrečiam tikslui aptarti ir terapiniam darbui pradėti.',
                    'en' => 'One individual session to clarify a specific goal and begin therapeutic work.',
                    'ru' => 'Одна индивидуальная сессия, чтобы определить конкретную цель и начать терапевтическую работу.',
                ],
                'meta' => [
                    'lt' => '1 konsultacija',
                    'en' => '1 consultation',
                    'ru' => '1 консультация',
                ],
                'price_display' => [
                    'lt' => '132 €',
                    'en' => '132 €',
                    'ru' => '132 €',
                ],
                'price_cents' => 13200,
                'requires_shipping' => false,
                'payable' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'phobia_program_4w',
                'badge' => [
                    'lt' => '4 savaičių programa',
                    'en' => 'Four-week programme',
                    'ru' => 'Программа на 4 недели',
                ],
                'title' => [
                    'lt' => 'Darbas su baime ar fobija',
                    'en' => 'Working with a fear or phobia',
                    'ru' => 'Работа со страхом или фобией',
                ],
                'description' => [
                    'lt' => 'Struktūruota keturių savaičių programa įsisenėjusiai baimei ar fobijai nagrinėti.',
                    'en' => 'A structured four-week programme for exploring an entrenched fear or phobia.',
                    'ru' => 'Структурированная четырёхнедельная программа для работы с устойчивым страхом или фобией.',
                ],
                'meta' => [
                    'lt' => 'Individuali programa',
                    'en' => 'Individual programme',
                    'ru' => 'Индивидуальная программа',
                ],
                'price_display' => [
                    'lt' => '600 €',
                    'en' => '600 €',
                    'ru' => '600 €',
                ],
                'price_cents' => 60000,
                'requires_shipping' => false,
                'payable' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'smoking_cessation',
                'badge' => [
                    'lt' => 'Viena kryptinga sesija',
                    'en' => 'One focused session',
                    'ru' => 'Одна целевая сессия',
                ],
                'title' => [
                    'lt' => 'Metimas rūkyti',
                    'en' => 'Smoking cessation',
                    'ru' => 'Отказ от курения',
                ],
                'description' => [
                    'lt' => 'Individuali kryptinga hipnoterapijos sesija žmogui, apsisprendusiam mesti rūkyti.',
                    'en' => 'An individual, focused hypnotherapy session for someone who has decided to stop smoking.',
                    'ru' => 'Индивидуальная целевая сессия гипнотерапии для человека, решившего отказаться от курения.',
                ],
                'meta' => [
                    'lt' => '1 konsultacija',
                    'en' => '1 consultation',
                    'ru' => '1 консультация',
                ],
                'price_display' => [
                    'lt' => '520 €',
                    'en' => '520 €',
                    'ru' => '520 €',
                ],
                'price_cents' => 52000,
                'requires_shipping' => false,
                'payable' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'book_science_change',
                'badge' => [
                    'lt' => 'Knyga',
                    'en' => 'Book',
                    'ru' => 'Книга',
                ],
                'title' => [
                    'lt' => 'Mokslu grįstas pokytis',
                    'en' => 'Science-informed change',
                    'ru' => 'Научно обоснованные изменения',
                ],
                'description' => [
                    'lt' => 'Praktinė knyga apie smegenų reakcijas, sutelktą dėmesį ir pokytį be kovos su savimi.',
                    'en' => 'A practical book about brain responses, focused attention and change without fighting yourself.',
                    'ru' => 'Практическая книга о реакциях мозга, сфокусированном внимании и изменениях без борьбы с собой.',
                ],
                'meta' => [
                    'lt' => 'Spausdinta knyga',
                    'en' => 'Printed book',
                    'ru' => 'Печатная книга',
                ],
                'price_display' => [
                    'lt' => '30 €',
                    'en' => '30 €',
                    'ru' => '30 €',
                ],
                'price_cents' => 3000,
                'requires_shipping' => true,
                'payable' => true,
                'sort_order' => 4,
            ],
            [
                'code' => 'group_training_matthew',
                'badge' => [
                    'lt' => 'Grupėms',
                    'en' => 'For groups',
                    'ru' => 'Для групп',
                ],
                'title' => [
                    'lt' => 'Hipnoterapijos mokymai su Matthew Cahill',
                    'en' => 'Hypnotherapy training with Matthew Cahill',
                    'ru' => 'Обучение гипнотерапии с Matthew Cahill',
                ],
                'description' => [
                    'lt' => 'Matthew Cahill vedami hipnoterapijos mokymai grupėms. Formatas, vieta, datos ir programa patvirtinami atskiru susitarimu.',
                    'en' => 'Group hypnotherapy training delivered by Matthew Cahill. Format, location, dates and programme are confirmed by separate agreement.',
                    'ru' => 'Обучение гипнотерапии для групп проводит Matthew Cahill. Формат, место, даты и программа подтверждаются отдельным соглашением.',
                ],
                'meta' => [
                    'lt' => 'Mokymus veda Matthew Cahill',
                    'en' => 'Delivered by Matthew Cahill',
                    'ru' => 'Обучение проводит Matthew Cahill',
                ],
                'price_display' => [
                    'lt' => 'Kaina derinama',
                    'en' => 'Price by agreement',
                    'ru' => 'Цена по согласованию',
                ],
                'price_cents' => null,
                'requires_shipping' => false,
                'payable' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['code' => $product['code']], $product);
        }
    }
}
