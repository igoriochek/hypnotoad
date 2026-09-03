"use client";

import { useEffect, useRef, useState } from "react";
import { Volume1, Volume2 } from "lucide-react";
import type { Locale } from "@/lib/site-content";

const volumeCopy = {
  lt: { louder: "Sustiprinti garsą", quieter: "Grąžinti tylų foną" },
  en: { louder: "Increase the sound", quieter: "Return to a quiet background" },
  ru: { louder: "Усилить звук", quieter: "Вернуть тихий фон" },
} as const;

export function YouTubeSoundscape({ videoId, label, lang, className }: { videoId: string; label: string; lang: Locale; className: string }) {
  const iframe = useRef<HTMLIFrameElement>(null);
  const timers = useRef<number[]>([]);
  const [quiet, setQuiet] = useState(true);

  const command = (func: string, args: unknown[] = []) => {
    iframe.current?.contentWindow?.postMessage(JSON.stringify({ event: "command", func, args }), "*");
  };

  const applyVolume = (volume: number) => {
    command("setVolume", [volume]);
    command("playVideo");
  };

  const ready = () => {
    timers.current.forEach(window.clearTimeout);
    timers.current = [250, 850, 1600].map((delay) => window.setTimeout(() => applyVolume(8), delay));
  };

  useEffect(() => () => timers.current.forEach(window.clearTimeout), []);

  const toggle = () => {
    const nextQuiet = !quiet;
    setQuiet(nextQuiet);
    applyVolume(nextQuiet ? 8 : 25);
  };

  const t = volumeCopy[lang];
  const src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&loop=1&playlist=${videoId}&rel=0&playsinline=1&enablejsapi=1`;

  return <div className={className}>
    <div className="youtube-soundscape-head">
      <span>{label}</span>
      <button type="button" onClick={toggle} aria-label={quiet ? t.louder : t.quieter} title={quiet ? t.louder : t.quieter}>
        {quiet ? <Volume1 size={16} /> : <Volume2 size={16} />}
        <small>{quiet ? "8%" : "25%"}</small>
      </button>
    </div>
    <iframe
      ref={iframe}
      src={src}
      title={label}
      allow="autoplay; encrypted-media; picture-in-picture"
      referrerPolicy="strict-origin-when-cross-origin"
      allowFullScreen
      onLoad={ready}
    />
  </div>;
}
