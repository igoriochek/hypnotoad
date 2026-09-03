"use client";

import Image from "next/image";
import { ChevronDown, Menu, X } from "lucide-react";
import { useEffect, useState } from "react";
import { homePaths, shopPaths, siteCopy, type Locale } from "@/lib/site-content";
import { contactEmail, contactPhone, contactPhoneDisplay } from "@/lib/site-config";

const languageLabels: Record<Locale, string> = { lt: "LT", en: "EN", ru: "RU" };

export function SiteHeader({ lang }: { lang: Locale }) {
  const [open, setOpen] = useState(false);
  const t = siteCopy[lang];

  useEffect(() => {
    document.body.classList.toggle("menu-open", open);
    const closeOnEscape = (event: KeyboardEvent) => {
      if (event.key === "Escape") setOpen(false);
    };
    window.addEventListener("keydown", closeOnEscape);
    return () => {
      document.body.classList.remove("menu-open");
      window.removeEventListener("keydown", closeOnEscape);
    };
  }, [open]);

  const close = () => setOpen(false);

  return (
    <header className="site-header">
      <a className="brand" href={homePaths[lang]} aria-label="Oksana Sakalauskienė">
        <span className="brand-mark" aria-hidden="true"><Image src="/oksana-symbol.png" alt="" width={707} height={618} /></span>
        <span><strong>Oksana Sakalauskienė</strong><small>{t.nav.brandLine}</small></span>
      </a>

      <nav className="desktop-nav" aria-label={t.nav.primaryLabel}>
        <a href="#smegenys">{t.nav.brain}</a>
        <a href="#kam">{t.nav.help}</a>
        <a href="#metodas">{t.nav.method}</a>
        <a href="#apie">{t.nav.about}</a>
        <div className="nav-resources">
          <button type="button">{t.nav.resources}<ChevronDown size={14} /></button>
          <div className="nav-resources-menu">
            <a href="#konsultacija">{t.nav.session}</a>
            <a href="#sleep-track">{t.nav.sleep}</a>
            <a href="#dazniai">{t.nav.frequencies}</a>
          </div>
        </div>
        <a href="#duk">{t.nav.faq}</a>
        <a href={shopPaths[lang]}>{t.nav.shop}</a>
      </nav>

      <div className="header-actions">
        <div className="lang-switch" aria-label={t.nav.languageLabel}>
          {(Object.keys(languageLabels) as Locale[]).map((locale) => (
            <a key={locale} href={homePaths[locale]} aria-current={locale === lang ? "page" : undefined}>{languageLabels[locale]}</a>
          ))}
        </div>
        <a className="nav-cta" href="#pokalbis">{t.nav.call}</a>
        <button
          className="mobile-menu-toggle"
          type="button"
          onClick={() => setOpen((value) => !value)}
          aria-expanded={open}
          aria-controls="mobile-navigation"
          aria-label={open ? t.nav.close : t.nav.menu}
        >
          {open ? <X size={20} /> : <Menu size={20} />}
        </button>
      </div>

      {open && (
        <div className="mobile-nav-panel" id="mobile-navigation">
          <nav aria-label={t.nav.mobileLabel}>
            <a href="#pradzia" onClick={close}><span>01</span>{t.nav.home}</a>
            <a href="#smegenys" onClick={close}><span>02</span>{t.nav.brain}</a>
            <a href="#kam" onClick={close}><span>03</span>{t.nav.help}</a>
            <a href="#metodas" onClick={close}><span>04</span>{t.nav.method}</a>
            <a href="#konsultacija" onClick={close}><span>05</span>{t.nav.session}</a>
            <a href="#apie" onClick={close}><span>06</span>{t.nav.about}</a>
            <a href="#sleep-track" onClick={close}><span>07</span>{t.nav.sleep}</a>
            <a href="#dazniai" onClick={close}><span>08</span>{t.nav.frequencies}</a>
            <a href="#duk" onClick={close}><span>09</span>{t.nav.faq}</a>
            <a href={shopPaths[lang]} onClick={close}><span>10</span>{t.nav.shop}</a>
            <a href="#pokalbis" onClick={close}><span>11</span>{t.nav.contact}</a>
          </nav>
          <div className="mobile-nav-footer">
            <a href={`tel:${contactPhone}`}>{contactPhoneDisplay}</a>
            <a href={`mailto:${contactEmail}`}>{contactEmail}</a>
          </div>
        </div>
      )}
    </header>
  );
}
