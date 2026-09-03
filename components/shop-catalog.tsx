"use client";

import { useMemo, useState } from "react";
import { ArrowRight, BookOpen, CalendarHeart, Check, Layers3, Minus, Plus, ShoppingBag, Sparkles, Trash2, Users } from "lucide-react";
import { contactEmail } from "@/lib/site-config";
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import type { Locale } from "@/lib/site-content";

type Product = {
  readonly badge: string;
  readonly title: string;
  readonly text: string;
  readonly meta: string;
  readonly price: string;
};

const productIcons = [Sparkles, Layers3, CalendarHeart, BookOpen, Users];

const labels = {
  lt: { add: "Įdėti į krepšelį", cart: "Krepšelis", empty: "Krepšelis tuščias", emptyText: "Pasirinkite paslaugą ar knygą — užklausą galėsite peržiūrėti prieš siunčiant.", send: "Siųsti užklausą Oksanai", remove: "Pašalinti", subject: "Užklausa iš svetainės parduotuvės", intro: "Sveiki, Oksana, norėčiau pasiteirauti dėl šių pasirinkimų:", note: "Paspaudus atsidarys jūsų el. pašto programa. Apmokėjimas svetainėje neatliekamas." },
  en: { add: "Add to cart", cart: "Cart", empty: "Your cart is empty", emptyText: "Choose a service or the book. You can review the enquiry before sending it.", send: "Email enquiry to Oksana", remove: "Remove", subject: "Enquiry from the website shop", intro: "Hello Oksana, I would like to enquire about:", note: "Your email app will open. No payment is taken on this website." },
  ru: { add: "Добавить в корзину", cart: "Корзина", empty: "Корзина пуста", emptyText: "Выберите услугу или книгу — перед отправкой запрос можно проверить.", send: "Отправить запрос Оксане", remove: "Удалить", subject: "Запрос из магазина на сайте", intro: "Здравствуйте, Оксана! Я хочу уточнить информацию по следующим позициям:", note: "Откроется ваша почтовая программа. Оплата на сайте не производится." },
} as const;

export function ShopCatalog({ lang, products, note, sectionLabel }: { lang: Locale; products: readonly Product[]; note: string; sectionLabel: string }) {
  const l = labels[lang];
  const [cart, setCart] = useState<Record<number, number>>({});
  const count = Object.values(cart).reduce((sum, quantity) => sum + quantity, 0);
  const selected = Object.entries(cart).filter(([, quantity]) => quantity > 0).map(([index, quantity]) => ({ product: products[Number(index)], index: Number(index), quantity }));

  const mailtoHref = useMemo(() => {
    const lines = selected.map(({ product, quantity }) => `• ${quantity} × ${product.title} — ${product.price}`);
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

  return (
    <section className="shop-catalog" aria-label={sectionLabel}>
      <div className="catalog-toolbar">
        <span>{products.length} / {products.length}</span>
        <p>{note}</p>
        <Sheet>
          <SheetTrigger asChild><button className="cart-trigger" type="button"><ShoppingBag size={18} />{l.cart}<b>{count}</b></button></SheetTrigger>
          <SheetContent className="cart-sheet">
            <SheetHeader><SheetTitle>{l.cart}</SheetTitle><SheetDescription>{l.note}</SheetDescription></SheetHeader>
            <div className="cart-items">
              {selected.length === 0 ? <div className="cart-empty"><ShoppingBag size={28} /><strong>{l.empty}</strong><p>{l.emptyText}</p></div> : selected.map(({ product, index, quantity }) => (
                <article className="cart-item" key={product.title}>
                  <div><strong>{product.title}</strong><span>{product.price}</span></div>
                  <div className="cart-quantity">
                    <button type="button" onClick={() => change(index, -1)} aria-label={`${l.remove}: ${product.title}`}><Minus size={15} /></button>
                    <b>{quantity}</b>
                    <button type="button" onClick={() => change(index, 1)} aria-label={`+ ${product.title}`}><Plus size={15} /></button>
                    <button className="cart-remove" type="button" onClick={() => change(index, -quantity)} aria-label={`${l.remove}: ${product.title}`}><Trash2 size={15} /></button>
                  </div>
                </article>
              ))}
            </div>
            {selected.length > 0 && <a className="cart-send" href={mailtoHref}>{l.send}<ArrowRight size={17} /></a>}
          </SheetContent>
        </Sheet>
      </div>
      <div className="product-grid">
        {products.map((product, index) => {
          const Icon = productIcons[index];
          return (
            <article className={`product-card product-${index + 1}`} key={product.title}>
              <div className="product-art"><span>0{index + 1}</span><Icon size={54} strokeWidth={1.15} /></div>
              <div className="product-body">
                <p className="product-badge">{product.badge}</p>
                <h2>{product.title}</h2>
                <p>{product.text}</p>
                <div className="product-meta"><span><Check size={15} />{product.meta}</span><strong>{product.price}</strong></div>
                <button className="product-order" type="button" onClick={() => add(index)}><ShoppingBag size={17} />{l.add}<ArrowRight size={17} /></button>
              </div>
            </article>
          );
        })}
      </div>
    </section>
  );
}
