<!DOCTYPE html>
@php
    $locale = in_array($locale ?? '', ['lt', 'en', 'ru'], true) ? $locale : 'lt';
    $t = [
        'lt' => [
            'title_paid' => 'Užsakymas apmokėtas',
            'title_pending' => 'Užsakymas laukia apmokėjimo',
            'title_cancelled' => 'Mokėjimas atšauktas',
            'title_error' => 'Užsakymas nerastas',
            'paid_text' => 'Ačiū! Mokėjimas patvirtintas. Patvirtinimą išsiuntėme el. paštu.',
            'pending_text' => 'Mokėjimas dar nepatvirtintas. Jei ką tik apmokėjote, palaukite kelias sekundes ir atnaujinkite puslapį.',
            'cancelled_text' => 'Mokėjimas buvo atšauktas arba nebaigtas. Užsakymo galite pateikti iš nauja parduotuvėje.',
            'error_text' => 'Nepavyko rasti užsakymo. Patikrinkite nuorodą arba susisiekite su mumis.',
            'order' => 'Užsakymas',
            'status' => 'Būsena',
            'total' => 'Iš viso',
            'items' => 'Pozicijos',
            'shipping_note' => 'Knyga bus išsiųsta nurodytu adresu.',
            'back' => 'Grįžti į svetainę',
            'contact' => 'Klausimai? rašykite',
            'paid' => 'apmokėta',
            'pending' => 'laukia apmokėjimo',
            'cancelled' => 'atšaukta',
        ],
        'en' => [
            'title_paid' => 'Order paid',
            'title_pending' => 'Order awaiting payment',
            'title_cancelled' => 'Payment cancelled',
            'title_error' => 'Order not found',
            'paid_text' => 'Thank you! Your payment is confirmed. A confirmation email has been sent to you.',
            'pending_text' => 'Payment is not confirmed yet. If you just paid, wait a few seconds and refresh this page.',
            'cancelled_text' => 'The payment was cancelled or not completed. You can place the order again in the shop.',
            'error_text' => 'We could not find this order. Check the link or contact us.',
            'order' => 'Order',
            'status' => 'Status',
            'total' => 'Total',
            'items' => 'Items',
            'shipping_note' => 'The book will be shipped to the address you provided.',
            'back' => 'Back to the site',
            'contact' => 'Questions? email',
            'paid' => 'paid',
            'pending' => 'awaiting payment',
            'cancelled' => 'cancelled',
        ],
        'ru' => [
            'title_paid' => 'Заказ оплачен',
            'title_pending' => 'Заказ ожидает оплаты',
            'title_cancelled' => 'Оплата отменена',
            'title_error' => 'Заказ не найден',
            'paid_text' => 'Спасибо! Оплата подтверждена. Подтверждение отправлено на вашу почту.',
            'pending_text' => 'Оплата ещё не подтверждена. Если вы только что оплатили, подождите несколько секунд и обновите страницу.',
            'cancelled_text' => 'Оплата была отменена или не завершена. Вы можете оформить заказ заново в магазине.',
            'error_text' => 'Не удалось найти заказ. Проверьте ссылку или свяжитесь с нами.',
            'order' => 'Заказ',
            'status' => 'Статус',
            'total' => 'Итого',
            'items' => 'Позиции',
            'shipping_note' => 'Книга будет отправлена по указанному адресу.',
            'back' => 'Вернуться на сайт',
            'contact' => 'Вопросы? пишите',
            'paid' => 'оплачен',
            'pending' => 'ожидает оплаты',
            'cancelled' => 'отменён',
        ],
    ][$locale];

    $hasError = isset($error);
    $isPaid = !$hasError && ($is_paid ?? false);
    $isCancelled = !$hasError && in_array($status ?? '', ['cancelled', 'failed', 'refunded'], true);

    $title = $hasError ? $t['title_error']
        : ($isPaid ? $t['title_paid']
        : ($isCancelled ? $t['title_cancelled'] : $t['title_pending']));
    $text = $hasError ? $t['error_text']
        : ($isPaid ? $t['paid_text']
        : ($isCancelled ? $t['cancelled_text'] : $t['pending_text']));
    $statusLabel = $isPaid ? $t['paid'] : ($isCancelled ? $t['cancelled'] : $t['pending']);
    $accent = $isPaid ? '#22c55e' : ($isCancelled ? '#f59e0b' : '#818cf8');
@endphp
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #0a0c10; --card: #141821; --border: #232936;
            --text: #e8eaf0; --muted: #8891a5; --accent: {{ $accent }};
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
        }
        .card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 16px; max-width: 520px; width: 100%; padding: 2.25rem;
        }
        .badge {
            display: inline-block; padding: 0.3rem 0.85rem; border-radius: 999px;
            font-size: 0.78rem; font-weight: 600; margin-bottom: 1rem;
            color: var(--accent); border: 1px solid var(--accent); opacity: 0.9;
        }
        h1 { font-size: 1.5rem; margin-bottom: 0.6rem; }
        .text { color: var(--muted); line-height: 1.55; margin-bottom: 1.5rem; }
        .row { display: flex; justify-content: space-between; padding: 0.55rem 0; border-bottom: 1px solid var(--border); font-size: 0.92rem; }
        .row span:first-child { color: var(--muted); }
        .items { margin: 1.25rem 0; }
        .items .row span:last-child { font-variant-numeric: tabular-nums; }
        .total { font-weight: 700; border-bottom: none; }
        .note { background: rgba(99,102,241,0.08); border: 1px solid var(--border); border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.88rem; color: var(--muted); margin-top: 1.25rem; }
        .links { margin-top: 1.75rem; display: flex; gap: 1.25rem; flex-wrap: wrap; font-size: 0.9rem; }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">{{ $hasError ? 'error' : $statusLabel }}</span>
        <h1>{{ $title }}</h1>
        <p class="text">{{ $text }}</p>

        @if (!$hasError)
            <div class="row"><span>{{ $t['order'] }}</span><span>{{ $order_number }}</span></div>
            <div class="row"><span>{{ $t['status'] }}</span><span>{{ $statusLabel }}</span></div>

            @if (!empty($items))
                <div class="items">
                    @foreach ($items as $item)
                        <div class="row">
                            <span>{{ $item['quantity'] }} × {{ $item['title'] }}</span>
                            <span>{{ number_format($item['line_total_cents'] / 100, 2, ',', '.') }} €</span>
                        </div>
                    @endforeach
                    <div class="row total"><span>{{ $t['total'] }}</span><span>{{ $total_formatted }}</span></div>
                </div>
            @endif

            @if ($isPaid && ($requires_shipping ?? false))
                <div class="note">{{ $t['shipping_note'] }}</div>
            @endif
        @endif

        <div class="links">
            <a href="/">{{ $t['back'] }}</a>
            <a href="mailto:roxana71@protonmail.com">{{ $t['contact'] }} roxana71@protonmail.com</a>
        </div>
    </div>
</body>
</html>
