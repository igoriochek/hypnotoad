import type { Metadata } from "next";
import "./globals.css";
import { publicSiteUrl } from "@/lib/site-config";

export const metadata: Metadata = {
  metadataBase: new URL(publicSiteUrl),
  title: {
    default: "Oksana Sakalauskienė | Klinikinė hipnoterapija",
    template: "%s",
  },
  description:
    "Klinikinės hipnoterapijos konsultacijos, padedančios sumažinti vidinį triukšmą, išgirsti kūno signalus ir sąmoningai kurti naują atsaką.",
  keywords: [
    "hipnoterapija",
    "klinikinė hipnoterapija",
    "hipnoterapijos konsultacija",
    "Oksana Sakalauskienė",
    "Inspiraology",
    "nerimo valdymas",
    "miego gerinimas",
    "įpročių keitimas",
    "kūno signalai",
    "kūno ir proto ryšys",
  ],
  authors: [{ name: "Oksana Sakalauskienė" }],
  creator: "Oksana Sakalauskienė",
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      "max-snippet": -1,
      "max-image-preview": "large",
    },
  },
  openGraph: {
    type: "website",
    locale: "lt_LT",
    title: "Oksana Sakalauskienė | Klinikinė hipnoterapija",
    description: "Išgirsti kūno signalus, suprasti save ir sąmoningai kurti naują atsaką.",
    images: [{ url: "/hypnotherapy-hero.png", width: 1024, height: 1024, alt: "Oksana Sakalauskienė — klinikinė hipnoterapija" }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Oksana Sakalauskienė | Klinikinė hipnoterapija",
    description: "Išgirsti kūno signalus, suprasti save ir sąmoningai kurti naują atsaką.",
    images: ["/hypnotherapy-hero.png"],
  },
  icons: {
    icon: "/favicon.svg",
    shortcut: "/favicon.svg",
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="lt">
      <body>{children}</body>
    </html>
  );
}
