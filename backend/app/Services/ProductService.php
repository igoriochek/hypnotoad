<?php

namespace App\Services;

use InvalidArgumentException;

class ProductService
{
    /**
     * Get a product's configuration by code.
     *
     * @param  string  $code
     * @return array|null
     */
    public function getProduct(string $code): ?array
    {
        $catalog = config('products');
        return $catalog[$code] ?? null;
    }

    /**
     * Get the localized title for a product.
     *
     * @param  string  $code
     * @param  string  $locale
     * @return string
     */
    public function getTitle(string $code, string $locale): string
    {
        $product = $this->getProduct($code);
        if (!$product) {
            throw new InvalidArgumentException("Unknown product code: {$code}");
        }
        return $product['title'][$locale] ?? $product['title']['en'];
    }

    /**
     * Get the price in cents for a product.
     *
     * @param  string  $code
     * @return int|null  Null for "price on request" items.
     */
    public function getPriceCents(string $code): ?int
    {
        $product = $this->getProduct($code);
        if (!$product) {
            throw new InvalidArgumentException("Unknown product code: {$code}");
        }
        return $product['price_cents'];
    }

    /**
     * Check if a product is payable (not "price on request").
     *
     * @param  string  $code
     * @return bool
     */
    public function isPayable(string $code): bool
    {
        $product = $this->getProduct($code);
        return $product && $product['payable'];
    }

    /**
     * Check if a product requires shipping.
     *
     * @param  string  $code
     * @return bool
     */
    public function requiresShipping(string $code): bool
    {
        $product = $this->getProduct($code);
        return $product && $product['requires_shipping'];
    }

    /**
     * Validate cart items and calculate the total in cents.
     * Only server-side prices are used — client-supplied prices are ignored.
     *
     * @param  array  $items  [['product_code' => '...', 'quantity' => 1], ...]
     * @return array{items: array, total_cents: int}
     * @throws InvalidArgumentException  If any product is invalid or not payable.
     */
    public function validateAndCalculate(array $items, string $locale): array
    {
        $validatedItems = [];
        $totalCents = 0;

        foreach ($items as $item) {
            $code = $item['product_code'];
            $quantity = (int) $item['quantity'];

            if ($quantity < 1) {
                throw new InvalidArgumentException("Invalid quantity for {$code}");
            }

            if (!$this->getProduct($code)) {
                throw new InvalidArgumentException("Unknown product code: {$code}");
            }

            if (!$this->isPayable($code)) {
                throw new InvalidArgumentException("Product {$code} is not payable via checkout");
            }

            $priceCents = $this->getPriceCents($code);
            $lineTotal = $priceCents * $quantity;
            $totalCents += $lineTotal;

            $validatedItems[] = [
                'product_code' => $code,
                'title_snapshot' => $this->getTitle($code, $locale),
                'unit_price_cents' => $priceCents,
                'quantity' => $quantity,
                'line_total_cents' => $lineTotal,
                'requires_shipping' => $this->requiresShipping($code),
            ];
        }

        if (empty($validatedItems)) {
            throw new InvalidArgumentException('Cart is empty');
        }

        return [
            'items' => $validatedItems,
            'total_cents' => $totalCents,
        ];
    }
}
