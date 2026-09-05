<!DOCTYPE html>
<html lang="{{ $order->locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Order confirmation') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>{{ $order->locale === 'lt' ? 'Užsakymo patvirtinimas' : ($order->locale === 'ru' ? 'Подтверждение заказа' : 'Order confirmation') }}</h1>

    <p>{{ $order->locale === 'lt' ? 'Ačiū už apmokėjimą!' : ($order->locale === 'ru' ? 'Спасибо за оплату!' : 'Thank you for your payment!') }}</p>

    <p><strong>{{ $order->locale === 'lt' ? 'Užsakymo nr.' : ($order->locale === 'ru' ? 'Заказ №' : 'Order no.') }}:</strong> {{ $order->order_number }}</p>
    <p><strong>{{ $order->locale === 'lt' ? 'Data' : ($order->locale === 'ru' ? 'Дата' : 'Date') }}:</strong> {{ $order->paid_at?->format('Y-m-d H:i') }}</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                <th style="padding: 8px;">{{ $order->locale === 'lt' ? 'Produktas' : ($order->locale === 'ru' ? 'Товар' : 'Product') }}</th>
                <th style="padding: 8px; text-align: center;">{{ $order->locale === 'lt' ? 'Kiekis' : ($order->locale === 'ru' ? 'Кол-во' : 'Qty') }}</th>
                <th style="padding: 8px; text-align: right;">{{ $order->locale === 'lt' ? 'Suma' : ($order->locale === 'ru' ? 'Сумма' : 'Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 8px;">{{ $item->title_snapshot }}</td>
                    <td style="padding: 8px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 8px; text-align: right;">{{ number_format($item->line_total_cents / 100, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="border-top: 2px solid #ddd;">
                <td colspan="2" style="padding: 8px; text-align: right;"><strong>{{ $order->locale === 'lt' ? 'Iš viso' : ($order->locale === 'ru' ? 'Итого' : 'Total') }}:</strong></td>
                <td style="padding: 8px; text-align: right;"><strong>{{ $totalFormatted }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if ($requiresShipping)
        <div style="background: #f0f9ff; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p><strong>{{ $order->locale === 'lt' ? 'Pristatymo adresas' : ($order->locale === 'ru' ? 'Адрес доставки' : 'Shipping address') }}:</strong></p>
            <p>{{ $order->customer_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
            {{ $order->shipping_country }}</p>
        </div>
    @endif

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
    <p style="color: #666; font-size: 0.9em;">shkelio.com — Oksana Sakalauskienė</p>
</body>
</html>
