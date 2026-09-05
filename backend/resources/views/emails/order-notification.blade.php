<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Naujas apmokėtas užsakymas</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Naujas apmokėtas užsakymas</h1>

    <p><strong>Užsakymo nr.:</strong> {{ $order->order_number }}</p>
    <p><strong>Klientas:</strong> {{ $order->customer_name }}</p>
    <p><strong>El. paštas:</strong> {{ $order->customer_email }}</p>
    @if ($order->customer_phone)
        <p><strong>Telefonas:</strong> {{ $order->customer_phone }}</p>
    @endif
    <p><strong>Apmokėta:</strong> {{ $order->paid_at?->format('Y-m-d H:i') }}</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                <th style="padding: 8px;">Produktas</th>
                <th style="padding: 8px; text-align: center;">Kiekis</th>
                <th style="padding: 8px; text-align: right;">Suma</th>
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
                <td colspan="2" style="padding: 8px; text-align: right;"><strong>Iš viso:</strong></td>
                <td style="padding: 8px; text-align: right;"><strong>{{ $totalFormatted }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if ($requiresShipping)
        <div style="background: #fff3cd; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p><strong>⚠ Knygos pristatymas — reikia perduoti siuntų sistemai:</strong></p>
            <p>{{ $order->customer_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
            {{ $order->shipping_country }}</p>
        </div>
    @endif

    <p style="margin-top: 30px;">
        <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}">Peržiūrėti užsakymą administravime</a>
    </p>
</body>
</html>
