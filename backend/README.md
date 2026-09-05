# shkelio.com — Laravel Payment Backend

Laravel backend for order management, payment processing, and webhook handling.

## Architecture

```
Frontend (Next.js)  →  POST /api/checkout  →  Creates order + payment
                     ←  Returns checkout URL
Browser  →  Redirected to provider Checkout (Montonio / Paysera / MakeCommerce)
Provider  →  POST /api/payments/webhook  →  Verifies signature, updates order
Browser  →  GET /order/success?order=...  →  Shows server-verified status
```

## Setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Configuration

### Payment Provider

Set in `.env` (or hosting secrets in production):

```env
PAYMENT_PROVIDER=montonio    # montonio | paysera | makecommerce
PAYMENT_ACCESS_KEY=your_key
PAYMENT_SECRET_KEY=your_secret
PAYMENT_WEBHOOK_SECRET=your_webhook_secret
ORDER_NOTIFICATION_EMAIL=roxana71@protonmail.com
```

### Product Catalog

Products and prices are defined in `config/products.php`. Prices are in **euro cents** (integers).
The server never trusts client-supplied prices — it looks up products by code.

| Code | Product | Price (cents) |
| --- | --- | ---: |
| `consultation_single` | Individual consultation | 13200 |
| `phobia_program_4w` | 4-week phobia program | 60000 |
| `smoking_cessation` | Smoking cessation | 52000 |
| `book_science_change` | Book | 3000 |
| `group_training_matthew` | Group training | Price on request (not payable) |

## API Routes

| Method | Path | Purpose |
| --- | --- | --- |
| `POST` | `/api/checkout` | Validate cart, create order, create payment, return redirect URL |
| `POST` | `/api/payments/webhook` | Receive signed webhook from payment provider |
| `GET` | `/order/success?order=...` | Show server-verified order status |
| `GET` | `/order/cancelled?order=...` | Show cancelled/incomplete payment |

## Order States

`pending` → `payment_started` → `paid` → `fulfilled`
                                    ↘ `failed`
                          ↘ `cancelled`
                          `paid` → `refunded`

## Security

- **No card data** is ever handled by this server — the provider's hosted Checkout is used.
- **API keys** are in hosting secrets, never in Git or `.env.example`.
- **Webhook signature** is verified for every request.
- **Amount and currency** are verified against the order in the DB.
- **Idempotency**: `provider_event_id` is unique — duplicate webhooks are ignored.
- **Success URL is not proof of payment** — only verified webhooks mark orders as `paid`.

## Testing

Before going live, test these scenarios in the provider's Sandbox:

1. Successful payment
2. Failed payment
3. Duplicate webhook (idempotency)
4. Invalid signature
5. Amount mismatch
6. Refund scenario

```bash
php artisan test
```

## Webhook CSRF Exclusion

The webhook endpoint is in `routes/api.php` which is automatically excluded from CSRF
protection in Laravel 11 (API routes use token/sanctum auth, not session-based CSRF).
