import { ExternalLink, Headphones, Waves } from "lucide-react";
import type { Locale } from "@/lib/site-content";

const tracks = [
  { hz: "432 Hz", url: "https://www.youtube.com/watch?v=MGMMB_z-DSo", title: "Generative Ambient Music", use: { lt: "Ramiam fonui, lėtam kvėpavimui ir dėmesio nuraminimui.", en: "For a calm background, slower breathing and settling attention.", ru: "Для спокойного фона, замедления дыхания и мягкой концентрации." } },
  { hz: "432 Hz", url: "https://www.youtube.com/watch?v=MNYICe0VXM8", title: "Gateway to Deep Sleep", use: { lt: "Vakaro rutinai, poilsiui ir pasiruošimui miegui.", en: "For an evening routine, rest and preparation for sleep.", ru: "Для вечернего ритуала, отдыха и подготовки ко сну." } },
  { hz: "Ambient", url: "https://www.youtube.com/watch?v=5uA5EHWwRHs", title: "Immersive Ambient Drones", use: { lt: "Gilesniam atsipalaidavimui ir meditacinei aplinkai be ryškaus ritmo.", en: "For deeper relaxation and a meditative setting without a pronounced rhythm.", ru: "Для глубокого расслабления и медитативной атмосферы без выраженного ритма." } },
  { hz: "528 Hz", url: "https://www.youtube.com/watch?v=rtljBHnbfNo", title: "Pure Tone", use: { lt: "Sutelktam klausymui, vizualizacijai arba ramiai refleksijai.", en: "For focused listening, visualisation or quiet reflection.", ru: "Для сосредоточенного прослушивания, визуализации или спокойной рефлексии." } },
  { hz: "852 Hz", url: "https://www.youtube.com/watch?v=7wAb8_STOs4", title: "Pure Tone", use: { lt: "Trumpai sąmoningumo pauzei ir dėmesio perkėlimui į dabartį.", en: "For a short mindful pause and returning attention to the present.", ru: "Для короткой осознанной паузы и возвращения внимания в настоящий момент." } },
  { hz: "963 Hz", url: "https://www.youtube.com/watch?v=6CohA5Ou8NY", title: "Pure Tone", use: { lt: "Trumpam, tyliam klausymui, kai norisi skaidresnio ir aukštesnio tono.", en: "For brief, quiet listening when a clearer, higher tone feels appropriate.", ru: "Для короткого тихого прослушивания, если вам подходит более высокий и чистый тон." } },
] as const;

const copy = {
  lt: { kicker: "Pasirinkite savo garsą", title: "Muzika klausymui", intro: "Skirtingi garsai gali padėti sukurti skirtingą klausymosi aplinką. Rinkitės pagal savijautą, laiką ir norimą patirtį — pradėkite tyliai.", listen: "Klausyti per YouTube", note: "Šie įrašai skirti atsipalaidavimo ir asmeninei klausymosi patirčiai. Nurodyti dažniai nėra medicininis gydymas ir nepakeičia profesionalios sveikatos priežiūros." },
  en: { kicker: "Choose your sound", title: "Music for listening", intro: "Different sounds can support different listening environments. Choose according to how you feel, the time of day and the experience you want — begin quietly.", listen: "Listen on YouTube", note: "These recordings are for relaxation and personal listening. The stated frequencies are not medical treatment and do not replace professional healthcare." },
  ru: { kicker: "Выберите свой звук", title: "Музыка для прослушивания", intro: "Разные звуки помогают создать разную атмосферу. Выбирайте по самочувствию, времени суток и желаемому ощущению — начинайте с тихой громкости.", listen: "Слушать на YouTube", note: "Эти записи предназначены для расслабления и личного прослушивания. Указанные частоты не являются лечением и не заменяют профессиональную медицинскую помощь." },
} as const;

export function FrequencyLibrary({ lang }: { lang: Locale }) {
  const t = copy[lang];
  return <section className="frequency-library section" id="dazniai">
    <div className="frequency-library-heading">
      <p className="kicker"><Waves size={16} />{t.kicker}</p>
      <h2>{t.title}</h2>
      <p>{t.intro}</p>
    </div>
    <div className="frequency-track-grid">
      {tracks.map((track) => <article className="frequency-track" key={track.url}>
        <span className="frequency-badge">{track.hz}</span>
        <Headphones size={23} strokeWidth={1.5} />
        <h3>{track.title}</h3>
        <p>{track.use[lang]}</p>
        <a href={track.url} target="_blank" rel="noreferrer">{t.listen}<ExternalLink size={15} /></a>
      </article>)}
    </div>
    <p className="frequency-note">{t.note}</p>
  </section>;
}
