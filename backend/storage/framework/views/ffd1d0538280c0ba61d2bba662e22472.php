<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name')); ?></title>
    <style>
        :root {
            --bg: #0f1117;
            --card: #1a1d27;
            --border: #2a2d3a;
            --text: #e4e4e7;
            --muted: #9ca3af;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --green: #22c55e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container { max-width: 640px; width: 100%; }
        .header { text-align: center; margin-bottom: 2.5rem; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--green);
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .dot {
            width: 8px; height: 8px;
            background: var(--green);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .subtitle { color: var(--muted); font-size: 0.9rem; margin-top: 0.4rem; }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 1rem;
        }
        .endpoint {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--border);
        }
        .endpoint:last-child { border-bottom: none; }
        .method {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.55rem;
            border-radius: 4px;
            min-width: 48px;
            text-align: center;
        }
        .method-get { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .method-post { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
        .path {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 0.85rem;
            color: var(--text);
        }
        .desc { color: var(--muted); font-size: 0.8rem; margin-left: auto; }
        .footer {
            text-align: center;
            color: var(--muted);
            font-size: 0.75rem;
            margin-top: 2rem;
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { color: var(--accent-hover); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="badge"><span class="dot"></span> Running</div>
            <h1><?php echo e(config('app.name')); ?></h1>
            <p class="subtitle">Payment backend API &middot; Laravel <?php echo e(app()->version()); ?></p>
        </div>

        <div class="card">
            <div class="card-title">API Endpoints</div>
            <div class="endpoint">
                <span class="method method-post">POST</span>
                <span class="path">/api/checkout</span>
                <span class="desc">Create order &amp; get payment URL</span>
            </div>
            <div class="endpoint">
                <span class="method method-post">POST</span>
                <span class="path">/api/payments/webhook</span>
                <span class="desc">Signed webhook from provider</span>
            </div>
            <div class="endpoint">
                <span class="method method-get">GET</span>
                <span class="path">/api/</span>
                <span class="desc">API info (JSON)</span>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Order Status Pages</div>
            <div class="endpoint">
                <span class="method method-get">GET</span>
                <span class="path">/order/success</span>
                <span class="desc">Server-verified success</span>
            </div>
            <div class="endpoint">
                <span class="method method-get">GET</span>
                <span class="path">/order/cancelled</span>
                <span class="desc">Cancelled payment</span>
            </div>
        </div>

        <div class="card">
            <div class="card-title">System</div>
            <div class="endpoint">
                <span class="method method-get">GET</span>
                <span class="path">/up</span>
                <span class="desc">Health check</span>
            </div>
        </div>

        <div class="footer">
            Payment provider: <strong><?php echo e(config('payments.default', 'none')); ?></strong>
            &middot; <a href="/api/">API JSON</a>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\matas\Desktop\shkelio-com-svetaine\backend\resources\views/welcome.blade.php ENDPATH**/ ?>