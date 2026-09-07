<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name')); ?></title>
    <style>
        :root {
            --bg: #0a0c10;
            --card: #141821;
            --card-hover: #1a1f2e;
            --border: #232936;
            --border-hover: #2f3648;
            --text: #e8eaf0;
            --muted: #8891a5;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --green: #22c55e;
            --amber: #f59e0b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            background-image: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(99, 102, 241, 0.08), transparent);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }
        .container { max-width: 720px; width: 100%; }
        .header { text-align: center; margin-bottom: 2rem; }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: var(--green);
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
        }
        .dot {
            width: 7px; height: 7px;
            background: var(--green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--green);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }
        h1 {
            font-size: 1.85rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #e8eaf0 0%, #8891a5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle { color: var(--muted); font-size: 0.85rem; margin-top: 0.5rem; }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        .stat-value { font-size: 1.3rem; font-weight: 700; }
        .stat-label { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.25rem; }
        .section { margin-bottom: 1.5rem; }
        .section-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 0.6rem;
            padding-left: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title::before {
            content: '';
            width: 3px; height: 14px;
            background: var(--accent);
            border-radius: 2px;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.15s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }
        .card:hover {
            background: var(--card-hover);
            border-color: var(--border-hover);
            transform: translateX(2px);
        }
        .method {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.22rem 0.5rem;
            border-radius: 4px;
            min-width: 46px;
            text-align: center;
            flex-shrink: 0;
        }
        .method-get { background: rgba(34, 197, 94, 0.12); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .method-post { background: rgba(99, 102, 241, 0.12); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.2); }
        .path {
            font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
            font-size: 0.82rem;
            color: var(--text);
            white-space: nowrap;
        }
        .desc { color: var(--muted); font-size: 0.78rem; margin-left: auto; }
        .arrow {
            color: var(--muted);
            font-size: 1rem;
            transition: color 0.15s, transform 0.15s;
            flex-shrink: 0;
        }
        .card:hover .arrow {
            color: var(--accent-hover);
            transform: translateX(3px);
        }
        .product {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .product-num { font-size: 0.7rem; font-weight: 700; color: var(--muted); min-width: 24px; }
        .product-info { flex: 1; min-width: 0; }
        .product-title { font-size: 0.85rem; font-weight: 600; }
        .product-desc { font-size: 0.75rem; color: var(--muted); margin-top: 0.15rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .product-price { font-size: 0.85rem; font-weight: 700; color: var(--green); flex-shrink: 0; }
        .product-price.negotiate { color: var(--amber); font-size: 0.75rem; }
        .footer {
            text-align: center;
            color: var(--muted);
            font-size: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        .footer a { color: var(--accent); text-decoration: none; transition: color 0.15s; }
        .footer a:hover { color: var(--accent-hover); }
        .footer-links { display: flex; gap: 0.75rem; justify-content: center; margin-top: 0.6rem; flex-wrap: wrap; }
        .footer-links a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.7rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 0.75rem;
            transition: all 0.15s;
        }
        .footer-links a:hover { border-color: var(--accent); background: rgba(99, 102, 241, 0.08); }
        @media (max-width: 520px) {
            .stats { grid-template-columns: 1fr; }
            .desc { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="status-badge"><span class="dot"></span> Running</div>
            <h1><?php echo e(config('app.name')); ?></h1>
            <p class="subtitle">Payment backend &middot; Laravel <?php echo e(app()->version()); ?> &middot; PHP <?php echo e(PHP_VERSION); ?></p>
        </div>

        <div class="stats">
            <div class="stat">
                <div class="stat-value" style="color: var(--green);"><?php echo e($productCount); ?></div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat">
                <div class="stat-value" style="color: var(--accent-hover);"><?php echo e($orderCount); ?></div>
                <div class="stat-label">Orders</div>
            </div>
            <div class="stat">
                <div class="stat-value" style="color: var(--amber);"><?php echo e($paidCount); ?></div>
                <div class="stat-label">Paid</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">API Endpoints</div>
            <a class="card" href="/api/products?locale=lt">
                <span class="method method-get">GET</span>
                <span class="path">/api/products</span>
                <span class="desc">Product catalog (JSON)</span>
                <span class="arrow">&rarr;</span>
            </a>
            <a class="card" href="/api/">
                <span class="method method-get">GET</span>
                <span class="path">/api/</span>
                <span class="desc">API info (JSON)</span>
                <span class="arrow">&rarr;</span>
            </a>
            <a class="card" href="javascript:void(0)" onclick="alert('POST endpoint — use curl or a REST client:\n\ncurl -X POST <?php echo e(url('/')); ?>/api/checkout \\\n  -H \"Content-Type: application/json\" \\\n  -H \"Accept: application/json\" \\\n  -d \'{\"locale\":\"lt\",\"customer_email\":\"test@test.com\",\"customer_name\":\"Test\",\"items\":[{\"product_code\":\"consultation_single\",\"quantity\":1}]}\'')">
                <span class="method method-post">POST</span>
                <span class="path">/api/checkout</span>
                <span class="desc">Create order &amp; get payment URL</span>
                <span class="arrow">&rarr;</span>
            </a>
            <a class="card" href="javascript:void(0)" onclick="alert('POST endpoint — receives signed webhooks from the payment provider.\n\nNot callable directly from browser.')">
                <span class="method method-post">POST</span>
                <span class="path">/api/payments/webhook</span>
                <span class="desc">Signed webhook receiver</span>
                <span class="arrow">&rarr;</span>
            </a>
        </div>

        <div class="section">
            <div class="section-title">Order Status Pages</div>
            <a class="card" href="/order/success?order=SHK-DEMO">
                <span class="method method-get">GET</span>
                <span class="path">/order/success</span>
                <span class="desc">Server-verified success</span>
                <span class="arrow">&rarr;</span>
            </a>
            <a class="card" href="/order/cancelled?order=SHK-DEMO">
                <span class="method method-get">GET</span>
                <span class="path">/order/cancelled</span>
                <span class="desc">Cancelled payment</span>
                <span class="arrow">&rarr;</span>
            </a>
        </div>

        <div class="section">
            <div class="section-title">System</div>
            <a class="card" href="/up">
                <span class="method method-get">GET</span>
                <span class="path">/up</span>
                <span class="desc">Health check</span>
                <span class="arrow">&rarr;</span>
            </a>
        </div>

        <div class="section">
            <div class="section-title">Product Catalog (<?php echo e($productCount); ?> items)</div>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product">
                <span class="product-num"><?php echo e(str_pad($p->sort_order, 2, '0', STR_PAD_LEFT)); ?></span>
                <div class="product-info">
                    <div class="product-title"><?php echo e($p->localizedTitle('lt')); ?></div>
                    <div class="product-desc"><?php echo e($p->localizedDescription('lt')); ?></div>
                </div>
                <?php if($p->payable): ?>
                <span class="product-price"><?php echo e($p->localizedPrice('lt')); ?></span>
                <?php else: ?>
                <span class="product-price negotiate"><?php echo e($p->localizedPrice('lt')); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="footer">
            Payment provider: <strong style="color: var(--text);"><?php echo e(config('payments.default', 'none')); ?></strong>
            <div class="footer-links">
                <a href="/api/products?locale=lt">Products JSON</a>
                <a href="/api/">API Info</a>
                <a href="/up">Health</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\matas\Desktop\shkelio-com-svetaine\backend\resources\views/welcome.blade.php ENDPATH**/ ?>