"use client";

import { Volume2 } from "lucide-react";

export function AmbientStartButton({ label }: { label: string }) {
  return <button
    className="button button-primary"
    type="button"
    onClick={() => window.dispatchEvent(new Event("ambient:focus"))}
  >
    {label}<Volume2 size={18} />
  </button>;
}
