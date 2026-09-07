import Image from "next/image";
import { ArrowLeft, Mail } from "lucide-react";
import { homePaths, shopPaths, siteCopy, type Locale } from "@/lib/site-content";
import { YouTubeSoundscape } from "@/components/youtube-soundscape";
import { contactEmail, contactPhone } from "@/lib/site-config";
import { ShopCatalog } from "@/components/shop-catalog";
import { ClientChat } from "@/components/client-chat";

const languageLabels: Record<Locale, string> = { lt: "LT", en: "EN", ru: "RU" };

export function ShopSite({ lang }: { lang: Locale }) {
  const t = siteCopy[lang];
  const soundtrackLabel = lang === "lt" ? "Garso fonas pasirinkimui" : lang === "en" ? "A soundscape for choosing" : "Звуковой фон для выбора";
  const itemListSchema = {
    "@context": "https://schema.org",
    "@type": "ItemList",
    inLanguage: lang,
    name: t.shop.eyebrow,
    itemListElement: t.shop.products.map((product, index) => ({
      "@type": "ListItem",
      position: index + 1,
      item: {
        "@type": "Service",
        name: product.title,
        description: product.text,
        ...(/\d/.test(product.price) ? { offers: { "@type": "Offer", price: product.price.replace(" €", ""), priceCurrency: "EUR" } } : {}),
        provider: { "@type": "Person", name: "Oksana Sakalauskienė", telephone: contactPhone, email: contactEmail },
      },
    })),
  };

  return (
    <main className="shop-page" lang={lang}>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(itemListSchema) }} />
      <header className="site-header">
        <a className="brand" href={homePaths[lang]} aria-label="Oksana Sakalauskienė">
          <span className="brand-mark" aria-hidden="true"><Image src="/oksana-symbol.png" alt="" width={707} height={618} /></span>
          <span><strong>Oksana Sakalauskienė</strong><small>Klinikinė hipnoterapija</small></span>
        </a>
        <nav aria-label="Primary navigation">
          <a href={homePaths[lang]}>{t.shop.back}</a>
          <a href={`mailto:${contactEmail}`}>{t.shop.order}</a>
        </nav>
        <div className="header-actions">
          <div className="lang-switch" aria-label="Language">
            {(Object.keys(languageLabels) as Locale[]).map((locale) => (
              <a key={locale} href={shopPaths[locale]} aria-current={locale === lang ? "page" : undefined}>{languageLabels[locale]}</a>
            ))}
          </div>
          <a className="nav-cta" href={`mailto:${contactEmail}`}>{t.shop.order}</a>
        </div>
      </header>

      <section className="shop-hero">
        <a className="back-link" href={homePaths[lang]}><ArrowLeft size={16} />{t.shop.back}</a>
        <div className="shop-hero-grid">
          <div><p className="eyebrow"><span />{t.shop.eyebrow}</p><h1>{t.shop.title}</h1></div>
          <div className="shop-hero-side">
            <p>{t.shop.lead}</p>
            <YouTubeSoundscape videoId="MGMMB_z-DSo" label={soundtrackLabel} lang={lang} className="shop-youtube" />
          </div>
        </div>
      </section>

      <ShopCatalog lang={lang} note={t.shop.note} sectionLabel={t.shop.eyebrow} />

      <section className="shop-callout">
        <div><p className="kicker kicker-light">{t.conversation.kicker}</p><h2>{t.conversation.title}</h2></div>
        <a className="contact-phone" href={`mailto:${contactEmail}`}><span>{t.shop.order}</span><strong>{contactEmail}</strong><Mail size={20} /></a>
      </section>

      <ClientChat lang={lang} />

      <footer>
        <div className="brand footer-brand"><span className="brand-mark"><Image src="/oksana-symbol.png" alt="" width={707} height={618} /></span><span><strong>Oksana Sakalauskienė</strong><small>Clinical hypnotherapy</small></span></div>
        <p>{t.disclaimer}</p><p>© {new Date().getFullYear()} Oksana Sakalauskienė</p>
      </footer>
    </main>
  );
}
