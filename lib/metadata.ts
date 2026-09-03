import type { Metadata } from "next";
import { homePaths, shopPaths, type Locale } from "@/lib/site-content";

const homeMeta = {
  lt: { title: "Oksana Sakalauskienė | Klinikinė hipnoterapija", description: "Klinikinės hipnoterapijos konsultacijos, padedančios sumažinti vidinį triukšmą, išgirsti kūno signalus ir sąmoningai kurti naują atsaką." },
  en: { title: "Oksana Sakalauskienė | Clinical Hypnotherapy", description: "Clinical hypnotherapy consultations that can help quieten inner noise, notice the body’s signals and consciously develop a new response." },
  ru: { title: "Оксана Сакалаускене | Клиническая гипнотерапия", description: "Консультации по клинической гипнотерапии, помогающие уменьшить внутренний шум, услышать сигналы тела и осознанно выбрать новую реакцию." },
};

const shopMeta = {
  lt: { title: "Konsultacijų parduotuvė | Oksana Sakalauskienė", description: "Pasirinkite individualią klinikinės hipnoterapijos konsultaciją arba konsultacijų paketą." },
  en: { title: "Consultation Shop | Oksana Sakalauskienė", description: "Choose an individual clinical hypnotherapy consultation or a focused consultation package." },
  ru: { title: "Магазин консультаций | Оксана Сакалаускене", description: "Выберите индивидуальную консультацию по клинической гипнотерапии или пакет консультаций." },
};

export function makeHomeMetadata(lang: Locale): Metadata {
  const meta = homeMeta[lang];
  return {
    ...meta,
    alternates: { canonical: homePaths[lang], languages: { "lt-LT": homePaths.lt, "en-GB": homePaths.en, "ru-RU": homePaths.ru, "x-default": homePaths.lt } },
    openGraph: {
      title: meta.title,
      description: meta.description,
      locale: lang === "lt" ? "lt_LT" : lang === "en" ? "en_GB" : "ru_RU",
      type: "website",
      images: [{ url: "/hypnotherapy-hero.png", width: 1024, height: 1024, alt: meta.title }],
    },
    twitter: { card: "summary_large_image", title: meta.title, description: meta.description, images: ["/hypnotherapy-hero.png"] },
  };
}

export function makeShopMetadata(lang: Locale): Metadata {
  const meta = shopMeta[lang];
  return {
    ...meta,
    alternates: { canonical: shopPaths[lang], languages: { "lt-LT": shopPaths.lt, "en-GB": shopPaths.en, "ru-RU": shopPaths.ru, "x-default": shopPaths.lt } },
    openGraph: {
      title: meta.title,
      description: meta.description,
      locale: lang === "lt" ? "lt_LT" : lang === "en" ? "en_GB" : "ru_RU",
      type: "website",
      images: [{ url: "/hypnotherapy-book-male.png", width: 1024, height: 1024, alt: meta.title }],
    },
    twitter: { card: "summary_large_image", title: meta.title, description: meta.description, images: ["/hypnotherapy-book-male.png"] },
  };
}
