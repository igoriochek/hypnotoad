<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $order->order_number }} — {{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #0a0c10; --card: #141821;
            --border: #232936; --text: #e8eaf0; --muted: #8891a5;
            --accent: #6366f1; --accent-hover: #818cf8;
            --green: #22c55e; --amber: #f59e0b; --red: #ef4444; --blue: #3b82f6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            background-image: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(99, 102, 241, 0.08), transparent);
            color: var(--text); min-height: 100vh; padding: 2.5rem 1.5rem;
        }
        .container { max-width: 640px; margin: 0 auto; }
        .header { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 1.5rem; }
        h1 { font-size: 1.35rem; font-weight: 700; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; }
        .back { color: var(--accent); text-decoration: none; font-size: 0.85rem; }
        .back:hover { color: var(--accent-hover); }
        .badge {
            display: inline-block; padding: 0.3rem 0.85rem; border-radius: 999px;
            font-size: 0.75rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
            margin-bottom: 1.25rem;
        }
        .b-paid { color: var(--green); border: 1px solid rgba(34,197,94,.4); background: rgba(34,197,94,.08); }
        .b-pending, .b-payment_started { color: var(--amber); border: 1px solid rgba(245,158,11,.4); background: rgba(245,158,11,.08); }
        .b-cancelled, .b-failed { color: var(--red); border: 1px solid rgba(239,68,68,.4); background: rgba(239,68,68,.08); }
        .b-fulfilled, .b-refunded { color: var(--blue); border: 1px solid rgba(59,130,246,.4); background: rgba(59,130,246,.08); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 1.1rem 1.25rem; margin-bottom: 0.9rem; }
        .card-title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); margin-bottom: 0.7rem; }
        .row { display: flex; justify-content: space-between; gap: 1rem; padding: 0.4rem 0; font-size: 0.88rem; }
        .row span:first-child { color: var(--muted); flex-shrink: 0; }
        .row span:last-child { text-align: right; word-break: break-word; }
        .total { font-weight: 700; font-size: 1rem; border-top: 1px solid var(--border); margin-top: 0.4rem; padding-top: 0.7rem; }
        .ship-box { border-color: rgba(99,102,241,.45); }
        .ship-addr { font-size: 0.9rem; line-height: 1.6; }
        .event { font-size: 0.78rem; color: var(--muted); padding: 0.3rem 0; border-bottom: 1px solid var(--border); }
        .event:last-child { border-bottom: none; }
        .event code { color: var(--text); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $order->order_number }}</h1>
            <a class="back" href="/orders">&larr; All orders</a>
        </div>

        <span class="badge b-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>

        <div class="card">
            <div class="card-title">Customer</div>
            <div class="row"><span>Name</span><span>{{ $order->customer_name }}</span></div>
            <div class="row"><span>Email</span><span>{{ $order->customer_email }}</span></div>
            @if ($order->customer_phone)
                <div class="row"><span>Phone</span><span>{{ $order->customer_phone }}</span></div>
            @endif
            <div class="row"><span>Locale</span><span>{{ $order->locale }}</span></div>
        </div>

        @if ($order->requiresShipping())
            <div class="card ship-box">
                <div class="card-title">&#128666; Shipping address</div>
                <p class="ship-addr">
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                    {{ $order->shipping_country }}
                </p>
            </div>
        @endif

        <div class="card">
            <div class="card-title">Items</div>
            @foreach ($order->items as $item)
                <div class="row">
                    <span>{{ $item->quantity }} × {{ $item->title_snapshot }}</span>
                    <span>{{ number_format($item->line_total_cents / 100, 2, ',', '.') }} €</span>
                </div>
            @endforeach
            <div class="row total"><span>Total</span><span>{{ $order->totalFormatted() }}</span></div>
        </div>

        <div class="card">
            <div class="card-title">Payment</div>
            <div class="row"><span>Provider</span><span>{{ $order->provider ?? '—' }}</span></div>
            <div class="row"><span>Payment ID</span><span>{{ $order->provider_payment_id ?? '—' }}</span></div>
            <div class="row"><span>Created</span><span>{{ $order->created_at->format('Y-m-d H:i:s') }}</span></div>
            @if ($order->paid_at)
                <div class="row"><span>Paid at</span><span>{{ $order->paid_at->format('Y-m-d H:i:s') }}</span></div>
            @endif
        </div>

        @if ($order->paymentEvents->isNotEmpty())
            <div class="card">
                <div class="card-title">Webhook events</div>
                @foreach ($order->paymentEvents as $event)
                    <div class="event">
                        <code>{{ $event->provider_event_id }}</code> — {{ $event->event_type }}
                        {{ $event->verified ? '✓ verified' : '✗ unverified' }}
                        · {{ $event->received_at?->format('Y-m-d H:i:s') }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
