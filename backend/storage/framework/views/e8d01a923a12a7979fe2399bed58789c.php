<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders — <?php echo e(config('app.name')); ?></title>
    <style>
        :root {
            --bg: #0a0c10; --card: #141821; --card-hover: #1a1f2e;
            --border: #232936; --border-hover: #2f3648;
            --text: #e8eaf0; --muted: #8891a5;
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
        .container { max-width: 860px; margin: 0 auto; }
        .header { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 1.75rem; }
        h1 { font-size: 1.6rem; font-weight: 700; letter-spacing: -0.03em; }
        .back { color: var(--accent); text-decoration: none; font-size: 0.85rem; }
        .back:hover { color: var(--accent-hover); }
        .order {
            display: grid; grid-template-columns: 1fr auto; gap: 0.35rem 1rem;
            background: var(--card); border: 1px solid var(--border); border-radius: 10px;
            padding: 0.9rem 1.1rem; margin-bottom: 0.6rem;
            text-decoration: none; color: var(--text); transition: all 0.15s ease;
        }
        .order:hover { background: var(--card-hover); border-color: var(--border-hover); transform: translateX(2px); }
        .order-num { font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: 0.85rem; font-weight: 600; }
        .order-meta { font-size: 0.78rem; color: var(--muted); }
        .order-right { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 0.35rem; }
        .order-total { font-weight: 700; font-size: 0.95rem; }
        .badge {
            display: inline-block; padding: 0.18rem 0.6rem; border-radius: 999px;
            font-size: 0.68rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
        }
        .b-paid { color: var(--green); border: 1px solid rgba(34,197,94,.4); background: rgba(34,197,94,.08); }
        .b-pending, .b-payment_started { color: var(--amber); border: 1px solid rgba(245,158,11,.4); background: rgba(245,158,11,.08); }
        .b-cancelled, .b-failed { color: var(--red); border: 1px solid rgba(239,68,68,.4); background: rgba(239,68,68,.08); }
        .b-fulfilled, .b-refunded { color: var(--blue); border: 1px solid rgba(59,130,246,.4); background: rgba(59,130,246,.08); }
        .ship { color: var(--accent-hover); font-size: 0.7rem; }
        .empty { text-align: center; color: var(--muted); padding: 3rem 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Orders (<?php echo e($orders->count()); ?>)</h1>
            <a class="back" href="/">&larr; Dashboard</a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a class="order" href="/orders/<?php echo e($order->order_number); ?>">
                <div>
                    <div class="order-num"><?php echo e($order->order_number); ?></div>
                    <div class="order-meta">
                        <?php echo e($order->customer_name); ?> &middot; <?php echo e($order->customer_email); ?>

                        &middot; <?php echo e($order->items->count()); ?> item<?php echo e($order->items->count() === 1 ? '' : 's'); ?>

                        &middot; <?php echo e($order->created_at->format('Y-m-d H:i')); ?>

                        <?php if($order->requiresShipping()): ?> <span class="ship">&#128666; shipping</span> <?php endif; ?>
                    </div>
                </div>
                <div class="order-right">
                    <span class="order-total"><?php echo e($order->totalFormatted()); ?></span>
                    <span class="badge b-<?php echo e($order->status); ?>"><?php echo e(str_replace('_', ' ', $order->status)); ?></span>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="empty">No orders yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH D:\Fork\hypnotoad\backend\resources\views/orders.blade.php ENDPATH**/ ?>