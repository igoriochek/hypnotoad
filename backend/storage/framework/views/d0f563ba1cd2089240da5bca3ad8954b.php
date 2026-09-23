<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($order->order_number); ?> — <?php echo e(config('app.name')); ?></title>
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
            <h1><?php echo e($order->order_number); ?></h1>
            <a class="back" href="/orders">&larr; All orders</a>
        </div>

        <span class="badge b-<?php echo e($order->status); ?>"><?php echo e(str_replace('_', ' ', $order->status)); ?></span>

        <div class="card">
            <div class="card-title">Customer</div>
            <div class="row"><span>Name</span><span><?php echo e($order->customer_name); ?></span></div>
            <div class="row"><span>Email</span><span><?php echo e($order->customer_email); ?></span></div>
            <?php if($order->customer_phone): ?>
                <div class="row"><span>Phone</span><span><?php echo e($order->customer_phone); ?></span></div>
            <?php endif; ?>
            <div class="row"><span>Locale</span><span><?php echo e($order->locale); ?></span></div>
        </div>

        <?php if($order->requiresShipping()): ?>
            <div class="card ship-box">
                <div class="card-title">&#128666; Shipping address</div>
                <p class="ship-addr">
                    <?php echo e($order->shipping_address); ?><br>
                    <?php echo e($order->shipping_postal_code); ?> <?php echo e($order->shipping_city); ?><br>
                    <?php echo e($order->shipping_country); ?>

                </p>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-title">Items</div>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row">
                    <span><?php echo e($item->quantity); ?> × <?php echo e($item->title_snapshot); ?></span>
                    <span><?php echo e(number_format($item->line_total_cents / 100, 2, ',', '.')); ?> €</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="row total"><span>Total</span><span><?php echo e($order->totalFormatted()); ?></span></div>
        </div>

        <div class="card">
            <div class="card-title">Payment</div>
            <div class="row"><span>Provider</span><span><?php echo e($order->provider ?? '—'); ?></span></div>
            <div class="row"><span>Payment ID</span><span><?php echo e($order->provider_payment_id ?? '—'); ?></span></div>
            <div class="row"><span>Created</span><span><?php echo e($order->created_at->format('Y-m-d H:i:s')); ?></span></div>
            <?php if($order->paid_at): ?>
                <div class="row"><span>Paid at</span><span><?php echo e($order->paid_at->format('Y-m-d H:i:s')); ?></span></div>
            <?php endif; ?>
        </div>

        <?php if($order->paymentEvents->isNotEmpty()): ?>
            <div class="card">
                <div class="card-title">Webhook events</div>
                <?php $__currentLoopData = $order->paymentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event">
                        <code><?php echo e($event->provider_event_id); ?></code> — <?php echo e($event->event_type); ?>

                        <?php echo e($event->verified ? '✓ verified' : '✗ unverified'); ?>

                        · <?php echo e($event->received_at?->format('Y-m-d H:i:s')); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH D:\Fork\hypnotoad\backend\resources\views/order-detail.blade.php ENDPATH**/ ?>