"use client";

import { useEffect, useRef, useState } from "react";
import { Volume2, VolumeX } from "lucide-react";
import type { Locale } from "@/lib/site-content";

const labels = {
  lt: { on: "Išjungti foninį garsą", off: "Įjungti foninį garsą" },
  en: { on: "Turn off ambient sound", off: "Turn on ambient sound" },
  ru: { on: "Выключить фоновый звук", off: "Включить фоновый звук" },
} as const;

type Graph = { context: AudioContext; master: GainNode; sources: AudioScheduledSourceNode[]; cycle?: number };
const ambientVolume = .085;

export function AmbientSound({ lang }: { lang: Locale }) {
  const graph = useRef<Graph | null>(null);
  const [active, setActive] = useState(false);

  useEffect(() => {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (!AudioContextClass) return;
    const context = new AudioContextClass();
    const master = context.createGain();
    const lowpass = context.createBiquadFilter();
    lowpass.type = "lowpass";
    lowpass.frequency.value = 680;
    lowpass.Q.value = .3;
    master.gain.setValueAtTime(0, context.currentTime);
    master.gain.linearRampToValueAtTime(ambientVolume, context.currentTime + 5);
    const droneBus = context.createGain();
    droneBus.connect(lowpass).connect(master);
    master.connect(context.destination);

    const sources: AudioScheduledSourceNode[] = [];
    const droneOscillators: OscillatorNode[] = [];
    [54, 108, 216].forEach((frequency, index) => {
      const oscillator = context.createOscillator();
      const gain = context.createGain();
      oscillator.type = index === 0 ? "sine" : "triangle";
      oscillator.frequency.value = frequency;
      oscillator.detune.value = index === 1 ? -4 : index === 2 ? 3 : 0;
      gain.gain.value = index === 0 ? .34 : index === 1 ? .12 : .028;
      oscillator.connect(gain).connect(droneBus);
      oscillator.start();
      sources.push(oscillator);
      droneOscillators.push(oscillator);
    });

    const airBuffer = context.createBuffer(1, context.sampleRate * 3, context.sampleRate);
    const data = airBuffer.getChannelData(0);
    let last = 0;
    for (let i = 0; i < data.length; i++) {
      last = last * .986 + (Math.random() * 2 - 1) * .014;
      data[i] = last * .38;
    }
    const air = context.createBufferSource();
    const airFilter = context.createBiquadFilter();
    const airGain = context.createGain();
    air.buffer = airBuffer;
    air.loop = true;
    airFilter.type = "bandpass";
    airFilter.frequency.value = 480;
    airFilter.Q.value = .42;
    airGain.gain.value = .014;
    air.connect(airFilter).connect(airGain).connect(droneBus);
    air.start();
    sources.push(air);

    const frequencyGains = [432, 528, 852, 963].map((frequency, index) => {
      const oscillator = context.createOscillator();
      const gain = context.createGain();
      oscillator.type = "sine";
      oscillator.frequency.value = frequency;
      gain.gain.value = index === 0 ? .018 : 0;
      oscillator.connect(gain).connect(master);
      oscillator.start();
      sources.push(oscillator);
      return gain;
    });
    let currentFrequency = 0;
    let texture = 0;
    const textures = [[54, 108, 216], [48, 96, 192], [60, 120, 240], [52, 104, 208]];
    const cycle = window.setInterval(() => {
      const now = context.currentTime;
      const nextFrequency = (currentFrequency + 1) % frequencyGains.length;
      frequencyGains[currentFrequency].gain.cancelScheduledValues(now);
      frequencyGains[nextFrequency].gain.cancelScheduledValues(now);
      frequencyGains[currentFrequency].gain.setValueAtTime(frequencyGains[currentFrequency].gain.value, now);
      frequencyGains[nextFrequency].gain.setValueAtTime(frequencyGains[nextFrequency].gain.value, now);
      frequencyGains[currentFrequency].gain.linearRampToValueAtTime(0, now + 10);
      frequencyGains[nextFrequency].gain.linearRampToValueAtTime(.018, now + 10);
      if (nextFrequency === 0) {
        texture = (texture + 1) % textures.length;
        droneOscillators.forEach((oscillator, index) => {
          oscillator.frequency.cancelScheduledValues(now);
          oscillator.frequency.setValueAtTime(oscillator.frequency.value, now);
          oscillator.frequency.exponentialRampToValueAtTime(textures[texture][index], now + 10);
        });
      }
      currentFrequency = nextFrequency;
    }, 60_000);
    graph.current = { context, master, sources, cycle };

    const focusAmbient = () => {
      void context.resume();
      const now = context.currentTime;
      master.gain.cancelScheduledValues(now);
      master.gain.setValueAtTime(master.gain.value, now);
      master.gain.linearRampToValueAtTime(.11, now + 4);
      frequencyGains.forEach((gain, index) => {
        gain.gain.cancelScheduledValues(now);
        gain.gain.setValueAtTime(gain.gain.value, now);
        gain.gain.linearRampToValueAtTime(index === 0 ? .022 : 0, now + 4);
      });
      currentFrequency = 0;
      setActive(true);
      document.documentElement.classList.add("soundscape-active");
    };
    window.addEventListener("ambient:focus", focusAmbient);

    const unlock = () => {
      void context.resume().then(() => {
        setActive(true);
        document.documentElement.classList.add("soundscape-active");
      });
      window.removeEventListener("pointerdown", unlock);
      window.removeEventListener("keydown", unlock);
      window.removeEventListener("touchstart", unlock);
      window.removeEventListener("ambient:focus", focusAmbient);
    };

    void context.resume().then(() => {
      if (context.state === "running") {
        setActive(true);
        document.documentElement.classList.add("soundscape-active");
      }
    });
    window.addEventListener("pointerdown", unlock, { once: true });
    window.addEventListener("keydown", unlock, { once: true });
    window.addEventListener("touchstart", unlock, { once: true });

    return () => {
      window.removeEventListener("pointerdown", unlock);
      window.removeEventListener("keydown", unlock);
      window.removeEventListener("touchstart", unlock);
      sources.forEach((source) => { try { source.stop(); } catch {} });
      window.clearInterval(cycle);
      void context.close();
      document.documentElement.classList.remove("soundscape-active");
    };
  }, []);

  const toggle = () => {
    const current = graph.current;
    if (!current) return;
    if (active) {
      const now = current.context.currentTime;
      current.master.gain.cancelScheduledValues(now);
      current.master.gain.setValueAtTime(current.master.gain.value, now);
      current.master.gain.linearRampToValueAtTime(0, now + 1.2);
      setActive(false);
      document.documentElement.classList.remove("soundscape-active");
    } else {
      void current.context.resume();
      const now = current.context.currentTime;
      current.master.gain.cancelScheduledValues(now);
      current.master.gain.setValueAtTime(current.master.gain.value, now);
      current.master.gain.linearRampToValueAtTime(ambientVolume, now + 2.5);
      setActive(true);
      document.documentElement.classList.add("soundscape-active");
    }
  };

  const t = labels[lang];
  return <button className="ambient-sound-toggle" type="button" onClick={toggle} aria-label={active ? t.on : t.off} title={active ? t.on : t.off}>
    {active ? <Volume2 size={17} /> : <VolumeX size={17} />}
  </button>;
}

declare global {
  interface Window { webkitAudioContext?: typeof AudioContext; }
}
