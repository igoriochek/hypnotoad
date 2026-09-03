import { Headphones, MoonStar } from "lucide-react";
import type { Locale } from "@/lib/site-content";

const copy = {
  lt: {
    kicker: "Nemokama praktika",
    title: "Sleep Track",
    text: "Švelni anglų kalbos atsipalaidavimo praktika prieš miegą. Patogiai įsitaisykite, sumažinkite garsą ir leiskite sau tiesiog klausytis.",
    language: "Anglų kalba",
    duration: "41 min.",
    soon: "Lietuvių ir rusų kalbomis — netrukus",
    label: "Leisti nemokamą Sleep Track anglų kalba",
  },
  en: {
    kicker: "Free practice",
    title: "Sleep Track",
    text: "A gentle English relaxation practice for bedtime. Settle comfortably, keep the volume low and allow yourself simply to listen.",
    language: "English",
    duration: "41 min",
    soon: "Lithuanian and Russian versions coming soon",
    label: "Play the free English Sleep Track",
  },
  ru: {
    kicker: "Бесплатная практика",
    title: "Sleep Track",
    text: "Мягкая англоязычная практика расслабления перед сном. Устройтесь удобно, установите тихую громкость и позвольте себе просто слушать.",
    language: "На английском",
    duration: "41 минута",
    soon: "Литовская и русская версии появятся позже",
    label: "Включить бесплатный Sleep Track на английском",
  },
} as const;

export function SleepTrack({ lang }: { lang: Locale }) {
  const t = copy[lang];
  return <section className="sleep-track-section" id="sleep-track" aria-labelledby="sleep-track-title">
    <div className="sleep-track-copy">
      <p className="kicker"><MoonStar size={15} />{t.kicker}</p>
      <h2 id="sleep-track-title">{t.title}</h2>
      <p>{t.text}</p>
    </div>
    <div className="sleep-track-player">
      <div className="sleep-track-meta"><span><Headphones size={18} />{t.language}</span><span>{t.duration}</span></div>
      <audio controls preload="metadata" controlsList="nodownload" aria-label={t.label}>
        <source src="/oksana-sleep-track-en.m4a" type="audio/mp4" />
      </audio>
      <small>{t.soon}</small>
    </div>
  </section>;
}
