"use client";

import { useEffect, useMemo, useState } from "react";
import { ArrowRight, BookOpen, CalendarHeart, Check, Layers3, Loader2, Minus, Plus, ShoppingBag, Sparkles, Trash2, Users } from "lucide-react";
import { contactEmail } from "@/lib/site-config";
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import type { Locale } from "@/lib/site-content";
import { fetchProducts, submitCheckout, type ApiProduct, type CheckoutItem, type ShippingInfo } from "@/lib/api";

type Product = {
  readonly badge: string;
  readonly title: string;
  readonly text: string;
  readonly meta: string;
  readonly price: string;
};

const productIcons = [Sparkles, Layers3, CalendarHeart, BookOpen, Users];

const labels = {
  lt: {
    add: "Įdėti į krepšelį",
    cart: "Krepšelis",
    empty: "Krepšelis tuščias",
    emptyText: "Pasirinkite paslaugą ar knygą — užklausą galėsite peržiūrėti prieš siunčiant.",
    checkout: "Apmokėti",
    loading: "Kraunama...",
    email: "El. paštas",
    name: "Vardas",
    phone: "Telefonas (neprivaloma)",
    emailPlaceholder: "vardas@pavyzdys.lt",
    namePlaceholder: "Jonas Jonaitis",
    phonePlaceholder: "+370 ...",
    payNote: "Būsite nukreipti į saugų mokėjimo puslapį.",
    errorNotPayable: "Šios paslaugos kaina derinama individualiai.",
    errorGeneric: "Įvyko klaida. Bandykite dar kartą.",
    errorEmpty: "Užpildykite el. paštą ir vardą.",
    errorShipping: "Knygai reikalingas pristatymo adresas — užpildykite visus pristatymo laukus.",
    shippingTitle: "Pristatymo adresas (knygai)",
    shippingAddress: "Gatvė ir namo numeris",
    shippingCity: "Miestas",
    shippingPostal: "Pašto kodas",
    shippingCountry: "Šalis",
    contactInstead: "Rašyti el. paštu",
    loadingProducts: "Kraunami produktai...",
    loadError: "Nepavyko įkelti produktų. Atnaujinkite puslapį.",
    send: "Siųsti užklausą Oksanai",
    remove: "Pašalinti",
    subject: "Užklausa iš svetainės parduotuvės",
    intro: "Sveiki, Oksana, norėčiau pasiteirauti dėl šių pasirinkimų:",
  },
  en: {
    add: "Add to cart",
    cart: "Cart",
    empty: "Your cart is empty",
    emptyText: "Choose a service or the book. You can review the enquiry before sending it.",
    checkout: "Checkout",
    loading: "Processing...",
    email: "Email",
    name: "Name",
    phone: "Phone (optional)",
    emailPlaceholder: "name@example.com",
    namePlaceholder: "John Doe",
    phonePlaceholder: "+370 ...",
    payNote: "You will be redirected to a secure payment page.",
    errorNotPayable: "This service is priced individually.",
    errorGeneric: "An error occurred. Please try again.",
    errorEmpty: "Please fill in email and name.",
    errorShipping: "The book requires a delivery address — please fill in all shipping fields.",
    shippingTitle: "Delivery address (for the book)",
    shippingAddress: "Street and house number",
    shippingCity: "City",
    shippingPostal: "Postal code",
    shippingCountry: "Country",
    contactInstead: "Email instead",
    loadingProducts: "Loading products...",
    loadError: "Failed to load products. Please refresh.",
    send: "Email enquiry to Oksana",
    remove: "Remove",
    subject: "Enquiry from the website shop",
    intro: "Hello Oksana, I would like to enquire about:",
  },
  ru: {
    add: "Добавить в корзину",
    cart: "Корзина",
    empty: "Корзина пуста",
    emptyText: "Выберите услугу или книгу — перед отправкой запрос можно проверить.",
    checkout: "Оплатить",
    loading: "Обработка...",
    email: "Эл. почта",
    name: "Имя",
    phone: "Телефон (необязательно)",
    emailPlaceholder: "imya@primer.com",
    namePlaceholder: "Иван Иванов",
    phonePlaceholder: "+370 ...",
    payNote: "Вы будете перенаправлены на безопасную страницу оплаты.",
    errorNotPayable: "Цена этой услуги согласуется индивидуально.",
    errorGeneric: "Произошла ошибка. Попробуйте ещё раз.",
    errorEmpty: "Заполните email и имя.",
    errorShipping: "Для книги нужен адрес доставки — заполните все поля доставки.",
    shippingTitle: "Адрес доставки (для книги)",
    shippingAddress: "Улица и номер дома",
    shippingCity: "Город",
    shippingPostal: "Почтовый индекс",
    shippingCountry: "Страна",
    contactInstead: "Написать по почте",
    loadingProducts: "Загрузка продуктов...",
    loadError: "Не удалось загрузить продукты. Обновите страницу.",
    send: "Отправить запрос Оксане",
    remove: "Удалить",
    subject: "Запрос из магазина на сайте",
    intro: "Здравствуйте, Оксана! Я хочу уточнить информацию по следующим позициям:",
  },
} as const;

export function ShopCatalog({ lang, note, sectionLabel }: { lang: Locale; products?: readonly Product[]; note: string; sectionLabel: string }) {
  const l = labels[lang];
  const [apiProducts, setApiProducts] = useState<ApiProduct[] | null>(null);
  const [loadError, setLoadError] = useState(false);
  const [cart, setCart] = useState<Record<number, number>>({});
  const [email, setEmail] = useState("");
  const [name, setName] = useState("");
  const [phone, setPhone] = useState("");
  const [shipAddress, setShipAddress] = useState("");
  const [shipCity, setShipCity] = useState("");
  const [shipPostal, setShipPostal] = useState("");
  const [shipCountry, setShipCountry] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    let cancelled = false;
    fetchProducts(lang)
      .then((products) => { if (!cancelled) setApiProducts(products); })
      .catch(() => { if (!cancelled) setLoadError(true); });
    return () => { cancelled = true; };
  }, [lang]);

  const products: ApiProduct[] = apiProducts ?? [];
  const count = Object.values(cart).reduce((sum, quantity) => sum + quantity, 0);
  const selected = Object.entries(cart)
    .filter(([, quantity]) => quantity > 0)
    .map(([index, quantity]) => ({ product: products[Number(index)], index: Number(index), quantity }));

  const hasNonPayable = selected.some((s) => s.product && !s.product.payable);
  const allPayable = selected.length > 0 && !hasNonPayable;
  const needsShipping = selected.some((s) => s.product?.requires_shipping);

  const mailtoHref = useMemo(() => {
    const lines = selected.filter((s) => s.product).map(({ product, quantity }) => `• ${quantity} × ${product.title} — ${product.price_display}`);
    const body = [l.intro, "", ...lines, "", "Vardas / Name / Имя:", "Telefonas / Phone / Телефон:"].join("\n");
    return `mailto:${contactEmail}?subject=${encodeURIComponent(l.subject)}&body=${encodeURIComponent(body)}`;
  }, [l.intro, l.subject, selected]);

  const add = (index: number) => setCart((current) => ({ ...current, [index]: (current[index] ?? 0) + 1 }));
  const change = (index: number, delta: number) => setCart((current) => {
    const next = Math.max(0, (current[index] ?? 0) + delta);
    const updated = { ...current };
    if (next === 0) delete updated[index]; else updated[index] = next;
    return updated;
  });

  async function handleCheckout() {
    setError(null);
    if (!email.trim() || !name.trim()) {
      setError(l.errorEmpty);
      return;
    }
    const items: CheckoutItem[] = selected
      .filter((s) => s.product && s.product.payable)
      .map(({ product, quantity }) => ({ product_code: product.code, quantity }));
    if (items.length === 0) {
      setError(l.errorNotPayable);
      return;
    }
    let shipping: ShippingInfo | undefined;
    if (needsShipping) {
      if (!shipAddress.trim() || !shipCity.trim() || !shipPostal.trim() || !shipCountry.trim()) {
        setError(l.errorShipping);
        return;
      }
      shipping = { address: shipAddress.trim(), city: shipCity.trim(), postal_code: shipPostal.trim(), country: shipCountry.trim() };
    }
    setSubmitting(true);
    try {
      const result = await submitCheckout(lang, items, { email: email.trim(), name: name.trim(), phone: phone.trim() || undefined }, shipping);
      window.location.href = result.checkout_url;
    } catch (e) {
      setError(e instanceof Error ? e.message : l.errorGeneric);
      setSubmitting(false);
    }
  }

  if (loadError) {
    return (
      <section className="shop-catalog" aria-label={sectionLabel}>
        <div className="catalog-toolbar"><p>{l.loadError}</p></div>
      </section>
    );
  }

  if (products.length === 0) {
    return (
      <section className="shop-catalog" aria-label={sectionLabel}>
        <div className="catalog-toolbar"><p>{l.loadingProducts}</p></div>
      </section>
    );
  }

  return (
    <section className="shop-catalog" aria-label={sectionLabel}>
      <div className="catalog-toolbar">
        <span>{products.length} / {products.length}</span>
        <p>{note}</p>
        <Sheet>
          <SheetTrigger asChild><button className="cart-trigger" type="button"><ShoppingBag size={18} />{l.cart}<b>{count}</b></button></SheetTrigger>
          <SheetContent className="cart-sheet">
            <SheetHeader><SheetTitle>{l.cart}</SheetTitle><SheetDescription>{l.payNote}</SheetDescription></SheetHeader>
            <div className="cart-items">
              {selected.length === 0 ? <div className="cart-empty"><ShoppingBag size={28} /><strong>{l.empty}</strong><p>{l.emptyText}</p></div> : selected.map(({ product, index, quantity }) => (
                <article className="cart-item" key={product.code}>
                  <div><strong>{product.title}</strong><span>{product.price_display}</span></div>
                  <div className="cart-quantity">
                    <button type="button" onClick={() => change(index, -1)} aria-label={`${l.remove}: ${product.title}`}><Minus size={15} /></button>
                    <b>{quantity}</b>
                    <button type="button" onClick={() => change(index, 1)} aria-label={`+ ${product.title}`}><Plus size={15} /></button>
                    <button className="cart-remove" type="button" onClick={() => change(index, -quantity)} aria-label={`${l.remove}: ${product.title}`}><Trash2 size={15} /></button>
                  </div>
                </article>
              ))}
            </div>
            {selected.length > 0 && (
              <div className="cart-checkout-form">
                <input type="email" placeholder={l.emailPlaceholder} value={email} onChange={(e) => setEmail(e.target.value)} autoComplete="email" />
                <input type="text" placeholder={l.namePlaceholder} value={name} onChange={(e) => setName(e.target.value)} autoComplete="name" />
                <input type="tel" placeholder={l.phonePlaceholder} value={phone} onChange={(e) => setPhone(e.target.value)} autoComplete="tel" />
                {needsShipping && (
                  <fieldset className="cart-shipping">
                    <legend>{l.shippingTitle}</legend>
                    <input type="text" placeholder={l.shippingAddress} value={shipAddress} onChange={(e) => setShipAddress(e.target.value)} autoComplete="street-address" />
                    <input type="text" placeholder={l.shippingCity} value={shipCity} onChange={(e) => setShipCity(e.target.value)} autoComplete="address-level2" />
                    <input type="text" placeholder={l.shippingPostal} value={shipPostal} onChange={(e) => setShipPostal(e.target.value)} autoComplete="postal-code" />
                    <input type="text" placeholder={l.shippingCountry} value={shipCountry} onChange={(e) => setShipCountry(e.target.value)} autoComplete="country-name" />
                  </fieldset>
                )}
                {error && <p className="cart-error">{error}</p>}
                {hasNonPayable && <p className="cart-warning">{l.errorNotPayable}</p>}
                {allPayable ? (
                  <button className="cart-send" type="button" onClick={handleCheckout} disabled={submitting}>
                    {submitting ? <><Loader2 size={17} className="animate-spin" />{l.loading}</> : <>{l.checkout}<ArrowRight size={17} /></>}
                  </button>
                ) : (
                  <a className="cart-send" href={mailtoHref}>{l.contactInstead}<ArrowRight size={17} /></a>
                )}
              </div>
            )}
          </SheetContent>
        </Sheet>
      </div>
      <div className="product-grid">
        {products.map((product, index) => {
          const Icon = productIcons[index] ?? Sparkles;
          return (
            <article className={`product-card product-${index + 1}`} key={product.code}>
              <div className="product-art"><span>{String(index + 1).padStart(2, "0")}</span><Icon size={54} strokeWidth={1.15} /></div>
              <div className="product-body">
                <p className="product-badge">{product.badge}</p>
                <h2>{product.title}</h2>
                <p>{product.description}</p>
                <div className="product-meta"><span><Check size={15} />{product.meta}</span><strong>{product.price_display}</strong></div>
                <button className="product-order" type="button" onClick={() => add(index)}><ShoppingBag size={17} />{l.add}<ArrowRight size={17} /></button>
              </div>
            </article>
          );
        })}
      </div>
    </section>
  );
}
