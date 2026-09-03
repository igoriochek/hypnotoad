import Image from "next/image";
import {
  ArrowDown,
  ArrowRight,
  Brain,
  Check,
  Leaf,
  Mail,
  Quote,
  ShieldCheck,
} from "lucide-react";
import { shopPaths, siteCopy, type Locale } from "@/lib/site-content";
import { BrainExperience } from "@/components/brain-experience";
import { AmbientSound } from "@/components/ambient-sound";
import { AmbientStartButton } from "@/components/ambient-start-button";
import { SleepTrack } from "@/components/sleep-track";
import { FrequencyLibrary } from "@/components/frequency-library";
import { ClientChat } from "@/components/client-chat";
import { ConcernExplorer } from "@/components/concern-explorer";
import { SiteHeader } from "@/components/site-header";
import { contactEmail, contactPhone, contactPhoneDisplay } from "@/lib/site-config";

const practiceContent = {
  lt: {
    kicker: "Konsultacijų kryptys",
    title: "Ką galime tyrinėti konsultacijų metu?",
    lead: "Kiekviena konsultacija pritaikoma žmogui ir jo tikslui. Toliau pateiktos temos nėra diagnozės ar rezultato pažadas — tai sritys, kuriose sprendimų orientuotas darbas gali padėti pastebėti reakcijas ir kurti naujus atsakus.",
    topics: [
      { title: "Nerimas ir ilgalaikis stresas", text: "Mokomės atpažinti, kaip stresas pasireiškia mintyse ir kūne, mažinti vidinę perkrovą bei stiprinti ramesnį atsaką į kasdienius dirgiklius." },
      { title: "Miegas ir atsistatymas", text: "Tyrinėjame vakarinį minčių aktyvumą, įtampą ir įpročius, kurie trukdo pereiti į poilsio būseną, o sesijoje repetuojame saugesnį sulėtėjimą." },
      { title: "Įpročiai ir rūkymo metimas", text: "Aiškinamės, kokią funkciją atlieka įprotis, kokie signalai jį įjungia ir kokius naujus pasirinkimus galima sustiprinti, kai sprendimas keistis jau priimtas." },
      { title: "Pasitikėjimas ir veiklos kokybė", text: "Darbas gali būti naudingas ruošiantis pokalbiui, scenai, varžyboms ar svarbiam sprendimui — dėmesys skiriamas aiškiam tikslui, mentalinei repeticijai ir susikaupimui." },
    ],
    source: "Kryptys parengtos originaliai Oksanos svetainei, remiantis Matthew Cahill ir Inspiraology sprendimų orientuotos hipnoterapijos mokyklos praktika.",
    sourceLabel: "Matthew Cahill mokyklos praktika",
  },
  en: {
    kicker: "Areas of consultation",
    title: "What can we explore during a consultation?",
    lead: "Every consultation is shaped around the person and their goal. These themes are not diagnoses or promises of outcome; they are areas where solution-focused work may help you notice patterns and practise new responses.",
    topics: [
      { title: "Anxiety and prolonged stress", text: "We notice how stress appears in thoughts and the body, reduce overload and strengthen a calmer response to everyday triggers." },
      { title: "Sleep and recovery", text: "We explore evening mental activity, tension and habits that make it difficult to move into rest, then rehearse a safer way to slow down." },
      { title: "Habits and stopping smoking", text: "We explore the role a habit serves, the cues that activate it and the new choices that can be strengthened once the decision to change has been made." },
      { title: "Confidence and performance", text: "Sessions may support preparation for a conversation, stage, competition or important decision through clear goals, mental rehearsal and focused attention." },
    ],
    source: "These themes were written specifically for Oksana’s website and are informed by the solution-focused hypnotherapy practice taught through Matthew Cahill and Inspiraology.",
    sourceLabel: "Matthew Cahill school practice",
  },
  ru: {
    kicker: "Направления консультаций",
    title: "Что можно исследовать во время консультации?",
    lead: "Каждая консультация адаптируется к человеку и его цели. Эти темы не являются диагнозом или обещанием результата — это направления, в которых ориентированная на решение работа может помочь заметить привычные реакции и сформировать новые ответы.",
    topics: [
      { title: "Тревога и длительный стресс", text: "Мы учимся замечать проявления стресса в мыслях и теле, снижать внутреннюю перегрузку и укреплять более спокойную реакцию на повседневные триггеры." },
      { title: "Сон и восстановление", text: "Мы исследуем вечернюю активность мыслей, напряжение и привычки, мешающие перейти к отдыху, а во время сеанса репетируем более безопасное замедление." },
      { title: "Привычки и отказ от курения", text: "Мы выясняем, какую функцию выполняет привычка, какие сигналы её запускают и какие новые выборы можно укрепить после принятого решения измениться." },
      { title: "Уверенность и результативность", text: "Работа может помочь при подготовке к разговору, выступлению, соревнованию или важному решению благодаря ясной цели, мысленной репетиции и концентрации." },
    ],
    source: "Темы написаны специально для сайта Оксаны на основе практики ориентированной на решение гипнотерапии, которой обучают Matthew Cahill и Inspiraology.",
    sourceLabel: "Практика школы Matthew Cahill",
  },
} as const;

export function TherapySite({ lang }: { lang: Locale }) {
  const t = siteCopy[lang];
  const practice = practiceContent[lang];
  const brainImage = lang === "ru" ? "/brain-explanation-ru.png" : lang === "en" ? "/brain-explanation-en.png" : "/brain-explanation-lt.png";
  const bookAlt = lang === "lt"
    ? "Švytintis žmogaus profilis, vaizduojantis sąmoningą pasirinkimą ir smegenų reakcijas"
    : lang === "en"
      ? "A luminous human profile representing conscious choice and brain responses"
      : "Светящийся профиль человека, символизирующий осознанный выбор и реакции мозга";
  const bookTagline = lang === "lt"
    ? "Sustabdyk visą pasaulio triukšmą ir išgirsi, kad visas pasaulis – tai tu, o jis – tavyje."
    : lang === "en"
      ? "Still the noise of the world, and you will hear that the whole world is you — and it lives within you."
      : "Останови весь шум мира, и ты услышишь, что весь мир — это ты, а он в тебе.";
  const contactOptions = lang === "lt" ? {
    next: "Pasiruošę kitam žingsniui?",
    oksanaLabel: "Rašyti Oksanai",
  } : lang === "en" ? {
    next: "Ready to take your next step?",
    oksanaLabel: "Email Oksana",
  } : {
    next: "Готовы сделать следующий шаг?",
    oksanaLabel: "Написать Оксане",
  };
  const faqSchema = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    inLanguage: lang,
    mainEntity: t.faqs.map((faq) => ({
      "@type": "Question",
      name: faq.question,
      acceptedAnswer: { "@type": "Answer", text: faq.answer },
    })),
  };
  const serviceSchema = {
    "@context": "https://schema.org",
    "@type": "Service",
    name: lang === "lt" ? "Klinikinės hipnoterapijos konsultacijos" : lang === "en" ? "Clinical hypnotherapy consultations" : "Консультации по клинической гипнотерапии",
    inLanguage: lang,
    provider: { "@type": "Person", name: "Oksana Sakalauskienė", telephone: contactPhone, email: contactEmail },
  };

  return (
    <main lang={lang}>
      <div className="frequency-tide" aria-hidden="true"><i data-hz="432" /><i data-hz="528" /><i data-hz="852" /><i data-hz="963" /></div>
      <AmbientSound lang={lang} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqSchema) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(serviceSchema) }} />

      <SiteHeader lang={lang} />

      <section className="hero" id="pradzia">
        <div className="hero-copy">
          <p className="eyebrow"><span />{t.hero.eyebrow}</p>
          <h1>{t.hero.titleBefore}<em>{t.hero.titleAccent}</em>{t.hero.titleAfter}</h1>
          <p className="hero-lead">{t.hero.lead}</p>
          <div className="hero-actions">
            <AmbientStartButton label={t.hero.primary} />
            <a className="text-link" href="#metodas">{t.hero.secondary}<ArrowDown size={17} /></a>
          </div>
          <div className="trust-line">
            <ShieldCheck size={20} /><span>{t.hero.qualified}</span><i /><span>{t.hero.uk}</span>
          </div>
        </div>
        <div className="hero-visual" id="smegenys">
          <div className="image-shell">
            <BrainExperience lang={lang} />
            <div className="focus-field" aria-hidden="true"><i /></div>
          </div>
        </div>
      </section>

      <section className="proof-strip" aria-label="Principles">
        {t.principles.map((principle, index) => <span className="proof-item" key={principle}><b>{principle}</b>{index < t.principles.length - 1 && <i>•</i>}</span>)}
      </section>

      <SleepTrack lang={lang} />
      <FrequencyLibrary lang={lang} />

      <section className="section concerns" id="kam">
        <div className="section-intro">
          <p className="kicker">{t.concernsIntro.kicker}</p><h2>{t.concernsIntro.title}</h2><p>{t.concernsIntro.text}</p>
        </div>
        <ConcernExplorer concerns={t.concerns} explanations={practice.topics} source={practice.source} sourceLabel={practice.sourceLabel} />
        <p className="care-note"><ShieldCheck size={18} />{t.careNote}</p>
      </section>

      <section className="method-section" id="metodas">
        <div className="method-heading">
          <p className="kicker kicker-light">{t.method.kicker}</p><h2>{t.method.title}</h2><p>{t.method.text}</p>
          <a className="source-link" href="https://inspiraology.com/" target="_blank" rel="noreferrer">{t.method.source}<ArrowRight size={16} /></a>
          <figure className="method-book">
            <div className="book-cover-art">
              <Image src={brainImage} alt={bookAlt} width={1256} height={1256} sizes="(max-width: 1050px) 92vw, 45vw" />
            </div>
            <figcaption><strong>{bookTagline}</strong></figcaption>
          </figure>
        </div>
        <div className="method-steps">
          {t.method.steps.map((step, index) => <article key={step.title}><span>0{index + 1}</span><div><h3>{step.title}</h3><p>{step.text}</p></div></article>)}
        </div>
      </section>

      <section className="session section" id="konsultacija">
        <div className="session-card"><p className="kicker">{t.session.kicker}</p><h2>{t.session.title}</h2><div className="session-points">{t.session.points.map((point) => <p key={point}><Check size={18} />{point}</p>)}</div><p className="session-explanation">{t.session.explanation}</p></div>
        <div className="session-quote"><Quote size={34} strokeWidth={1.3} /><blockquote>{t.session.quote}</blockquote><p>— Oksana Sakalauskienė</p></div>
      </section>

      <section className="about section" id="apie">
        <div className="about-monogram" aria-hidden="true"><span>O</span><span>S</span></div>
        <div className="about-copy"><p className="kicker">{t.about.kicker}</p><h2>{t.about.title}</h2><p className="about-lead">{t.about.lead}</p><p>{t.about.text}</p><div className="credentials"><span><ShieldCheck size={18} />{t.about.credentials[0]}</span><span><Leaf size={18} />{t.about.credentials[1]}</span><span><Brain size={18} />{t.about.credentials[2]}</span></div></div>
      </section>

      <section className="shop-teaser">
        <p className="kicker">{t.shop.eyebrow}</p><h2>{t.shop.title}</h2><p>{t.shop.lead}</p>
        <a className="button button-primary" href={shopPaths[lang]}>{t.nav.shop}<ArrowRight size={18} /></a>
      </section>

      <section className="faq section" id="duk">
        <div className="faq-heading"><p className="kicker">{t.faqIntro.kicker}</p><h2>{t.faqIntro.title}</h2><p>{t.faqIntro.text}</p></div>
        <div className="faq-list">{t.faqs.map((faq, index) => <details key={faq.question} open={index === 0}><summary><span>{faq.question}</span><i aria-hidden="true">+</i></summary><p>{faq.answer}</p></details>)}</div>
      </section>

      <section className="conversation" id="pokalbis">
        <div><p className="kicker kicker-light">{t.conversation.kicker}</p><h2>{t.conversation.title}</h2></div>
        <div className="conversation-copy">
          <p>{t.conversation.text}</p>
          <h3>{contactOptions.next}</h3>
          <a className="contact-phone" href={`tel:${contactPhone}`}><span>{t.conversation.phoneLabel}</span><strong>{contactPhoneDisplay}</strong><ArrowRight size={20} /></a>
          <a className="contact-phone" href={`mailto:${contactEmail}`}><span>{contactOptions.oksanaLabel}</span><strong>{contactEmail}</strong><Mail size={20} /></a>
        </div>
      </section>

      <ClientChat lang={lang} />

      <footer>
        <div className="brand footer-brand"><span className="brand-mark"><Image src="/oksana-symbol.png" alt="" width={707} height={618} /></span><span><strong>Oksana Sakalauskienė</strong><small>Clinical hypnotherapy</small></span></div>
        <p>{t.disclaimer}</p><p>© {new Date().getFullYear()} Oksana Sakalauskienė</p>
      </footer>
    </main>
  );
}
