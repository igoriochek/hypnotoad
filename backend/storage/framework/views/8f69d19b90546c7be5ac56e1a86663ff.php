<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test payment — <?php echo e(config('app.name')); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: #0a0c10; color: #e8eaf0;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
        }
        .card {
            background: #141821; border: 1px solid #232936;
            border-radius: 16px; max-width: 460px; width: 100%; padding: 2.25rem;
        }
        .badge {
            display: inline-block; padding: 0.3rem 0.85rem; border-radius: 999px;
            font-size: 0.75rem; font-weight: 700; margin-bottom: 1rem;
            color: #f59e0b; border: 1px solid #f59e0b; letter-spacing: 0.05em;
        }
        h1 { font-size: 1.4rem; margin-bottom: 0.5rem; }
        .sub { color: #8891a5; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .row { display: flex; justify-content: space-between; padding: 0.55rem 0; border-bottom: 1px solid #232936; font-size: 0.92rem; }
        .row span:first-child { color: #8891a5; }
        .total { font-weight: 700; }
        .actions { margin-top: 1.75rem; display: grid; gap: 10px; }
        button, .cancel {
            display: block; width: 100%; padding: 0.9rem; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700; text-align: center; cursor: pointer;
            text-decoration: none; border: none; font-family: inherit;
        }
        button { background: #22c55e; color: #04120a; }
        button:hover { background: #4ade80; }
        .cancel { background: transparent; color: #8891a5; border: 1px solid #232936; }
        .cancel:hover { color: #e8eaf0; border-color: #2f3648; }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">TEST GATEWAY — not a real payment</span>
        <h1>Test payment page</h1>
        <p class="sub">This simulates the provider's hosted checkout. Clicking "Pay" sends a real signed webhook to the backend.</p>

        <div class="row"><span>Order</span><span><?php echo e($order->order_number); ?></span></div>
        <div class="row"><span>Customer</span><span><?php echo e($order->customer_name); ?></span></div>
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row"><span><?php echo e($item->quantity); ?> × <?php echo e($item->title_snapshot); ?></span><span><?php echo e(number_format($item->line_total_cents / 100, 2, ',', '.')); ?> €</span></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="row total"><span>Total</span><span><?php echo e($total); ?></span></div>

        <div class="actions">
            <form method="POST" action="/test-payment/complete">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="order" value="<?php echo e($order->order_number); ?>">
                <button type="submit">Pay <?php echo e($total); ?> (test)</button>
            </form>
            <a class="cancel" href="/test-payment/cancel?order=<?php echo e($order->order_number); ?>">Cancel payment</a>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Fork\hypnotoad\backend\resources\views/test-payment.blade.php ENDPATH**/ ?>