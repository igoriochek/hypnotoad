import type { Locale } from "@/lib/site-content";

const API_BASE =
  process.env.NEXT_PUBLIC_API_URL ||
  (typeof window !== "undefined" && window.location.hostname === "localhost"
    ? "http://localhost:8000"
    : "https://api.shkelio.com");

export type ApiProduct = {
  code: string;
  badge: string;
  title: string;
  description: string;
  meta: string;
  price_display: string;
  price_cents: number | null;
  payable: boolean;
  requires_shipping: boolean;
  sort_order: number;
};

export type CheckoutItem = {
  product_code: string;
  quantity: number;
};

export type CheckoutResponse = {
  order_number: string;
  checkout_url: string;
};

export type CheckoutError = {
  error: string;
  message: string;
};

export async function fetchProducts(locale: Locale): Promise<ApiProduct[]> {
  const res = await fetch(`${API_BASE}/api/products?locale=${locale}`, {
    headers: { Accept: "application/json" },
  });
  if (!res.ok) throw new Error(`Failed to fetch products: ${res.status}`);
  return res.json();
}

export type ShippingInfo = {
  address: string;
  city: string;
  postal_code: string;
  country: string;
};

export async function submitCheckout(
  locale: Locale,
  items: CheckoutItem[],
  customer: { email: string; name: string; phone?: string },
  shipping?: ShippingInfo,
): Promise<CheckoutResponse> {
  const res = await fetch(`${API_BASE}/api/checkout`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({
      locale,
      customer_email: customer.email,
      customer_name: customer.name,
      customer_phone: customer.phone || undefined,
      items,
      ...(shipping
        ? {
            shipping_address: shipping.address,
            shipping_city: shipping.city,
            shipping_postal_code: shipping.postal_code,
            shipping_country: shipping.country,
          }
        : {}),
    }),
  });

  const data = await res.json();

  if (!res.ok) {
    const err = data as CheckoutError;
    throw new Error(err.message || err.error || "Checkout failed");
  }

  return data as CheckoutResponse;
}

export { API_BASE };
