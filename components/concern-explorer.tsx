"use client";

import { useState } from "react";
import { ArrowRight, MoonStar, Sparkles, Target, Waves } from "lucide-react";

type Concern = { readonly title: string; readonly text: string };
type Explanation = { readonly title: string; readonly text: string };

const icons = [Waves, MoonStar, Sparkles, Target];

export function ConcernExplorer({ concerns, explanations, source, sourceLabel }: { concerns: readonly Concern[]; explanations: readonly Explanation[]; source: string; sourceLabel: string }) {
  const [selected, setSelected] = useState(0);
  const active = explanations[selected];

  return (
    <>
      <div className="concern-grid">
        {concerns.map((concern, index) => {
          const Icon = icons[index];
          const isActive = index === selected;
          return (
            <button className={`concern-card${isActive ? " concern-card-active" : ""}`} type="button" key={concern.title} onClick={() => setSelected(index)} aria-expanded={isActive} aria-controls="concern-detail">
              <span className="card-number">0{index + 1}</span><Icon size={25} strokeWidth={1.6} /><h3>{concern.title}</h3><p>{concern.text}</p><span className="concern-open"><ArrowRight size={15} />{isActive ? "—" : "+"}</span>
            </button>
          );
        })}
      </div>
      <div className="concern-detail" id="concern-detail" aria-live="polite">
        <span className="concern-detail-number">0{selected + 1}</span>
        <div><h3>{active.title}</h3><p>{active.text}</p><small>{source}</small><a href="https://matthewcahill.co.uk/consultation/" target="_blank" rel="noreferrer">{sourceLabel}<ArrowRight size={16} /></a></div>
      </div>
    </>
  );
}
