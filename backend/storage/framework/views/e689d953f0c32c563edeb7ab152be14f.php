<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Naujas apmokėtas užsakymas</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Naujas apmokėtas užsakymas</h1>

    <p><strong>Užsakymo nr.:</strong> <?php echo e($order->order_number); ?></p>
    <p><strong>Klientas:</strong> <?php echo e($order->customer_name); ?></p>
    <p><strong>El. paštas:</strong> <?php echo e($order->customer_email); ?></p>
    <?php if($order->customer_phone): ?>
        <p><strong>Telefonas:</strong> <?php echo e($order->customer_phone); ?></p>
    <?php endif; ?>
    <p><strong>Apmokėta:</strong> <?php echo e($order->paid_at?->format('Y-m-d H:i')); ?></p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                <th style="padding: 8px;">Produktas</th>
                <th style="padding: 8px; text-align: center;">Kiekis</th>
                <th style="padding: 8px; text-align: right;">Suma</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 8px;"><?php echo e($item->title_snapshot); ?></td>
                    <td style="padding: 8px; text-align: center;"><?php echo e($item->quantity); ?></td>
                    <td style="padding: 8px; text-align: right;"><?php echo e(number_format($item->line_total_cents / 100, 2, ',', '.')); ?> €</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr style="border-top: 2px solid #ddd;">
                <td colspan="2" style="padding: 8px; text-align: right;"><strong>Iš viso:</strong></td>
                <td style="padding: 8px; text-align: right;"><strong><?php echo e($totalFormatted); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <?php if($requiresShipping): ?>
        <div style="background: #fff3cd; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p><strong>⚠ Knygos pristatymas — reikia perduoti siuntų sistemai:</strong></p>
            <p><?php echo e($order->customer_name); ?><br>
            <?php echo e($order->shipping_address); ?><br>
            <?php echo e($order->shipping_postal_code); ?> <?php echo e($order->shipping_city); ?><br>
            <?php echo e($order->shipping_country); ?></p>
        </div>
    <?php endif; ?>

    <p style="margin-top: 30px;">
        <a href="<?php echo e(config('app.url')); ?>/admin/orders/<?php echo e($order->id); ?>">Peržiūrėti užsakymą administravime</a>
    </p>
</body>
</html>
<?php /**PATH D:\Fork\hypnotoad\backend\resources\views/emails/order-notification.blade.php ENDPATH**/ ?>