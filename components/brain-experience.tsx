"use client";

import { useCallback, useEffect, useState } from "react";
import Image from "next/image";
import { Play, X } from "lucide-react";
import type { Locale } from "@/lib/site-content";
import { YouTubeSoundscape } from "@/components/youtube-soundscape";

const content = {
  lt: {
    title: "Smegenų darbo paaiškinimas",
    open: "Atverti smegenų darbo paaiškinimą per visą ekraną",
    close: "Uždaryti",
    previous: "Ankstesnis",
    next: "Toliau",
    listen: "Klausyti",
    stop: "Sustabdyti",
    soundtrack: "Smegenų darbo paaiškinimo garso fonas",
    progress: "Paaiškinimo žingsnis",
    intro: "Norėčiau šiek tiek papasakoti apie smegenis ir parodyti, kaip skirtingos jų dalys veikia kartu. Jūsų smegenys nekovoja su jumis — jos bando jus apsaugoti, kartais remdamosi sena informacija.",
    steps: [
      ["Intelektualiosios smegenys", "Sąmoningoji smegenų dalis yra priekinė žievė, kurioje glūdi jūsų sąmoningumas. Ją galima vadinti įmonės viršininku arba generaliniu direktoriumi, nes ji valdo aukštesniąsias vykdomąsias funkcijas: planavimą ir organizavimą, kalbos supratimą, valingus judesius, savikontrolę ir emocijų reguliavimą. Priekinė žievė yra susijusi su likusia smegenų žieve — pasąmonine dalimi. Sąmoningos smegenys vienu metu gali apdoroti tik kelis informacijos vienetus, o pasąmoninė dalis — milijonus. Visa žievė yra protingoji, mąstanti smegenų dalis. Ji leidžia mąstyti, racionalizuoti, analizuoti, vartoti kalbą, suvokti savo egzistavimą ir planuoti ateitį. Ji logiškai ir subalansuotai vertina situaciją, mato ilgalaikius tikslus ir gali atidėti momentinį pasitenkinimą."],
      ["Primityviosios smegenys", "Primityvioji proto dalis dar vadinama limbine sistema arba išlikimo smegenimis. Joje yra migdolinis kūnas — smegenų saugos pareigūnas. Jis nuolat vertina aplinką ir tikrina, ar esate saugūs. Aptikęs grėsmę jis įjungia kovos, bėgimo, sustingimo arba prisitaikymo reakciją. Jam padeda hipokampas — visų patirčių, įpročių ir elgesio modelių archyvas. Patirtys jame saugomos kartu su emocine žyme. Emocijos veikia kaip itin greita informacijos paieškos sistema, todėl smegenys gali akimirksniu reaguoti į tai, kas anksčiau buvo susiję su skausmu ar pavojumi."],
      ["Pavojaus signalas kūne", "Kai hipokampas atpažįsta pavojų, migdolinis kūnas įjungia apsaugos reakciją, o pagumburis — tarsi smegenų vaistininkas — paskatina kortizolio ir adrenalino išsiskyrimą. Širdis ima plakti greičiau, kvėpavimas tampa dažnas ir paviršutiniškas, oda gali prakaituoti. Kraujas nukreipiamas į raumenis, todėl galite jausti svaigulį ar silpnumą, sutrikusį skrandį. Tuo metu intelektualiosios smegenys laikinai užleidžia valdymą primityviosioms. Tai puiki reakcija susidūrus su tikru pavojumi, tačiau ji mažiau naudinga prieš egzaminą, svarbų pokalbį, pasimatymą ar viešą kalbą. Apsaugos sistema gali priminti ankstesnes nesėkmes, prognozuoti blogiausią scenarijų ar sukelti fizinius simptomus, kad išvengtumėte situacijos. Ji nemėgsta pokyčių ir skatina kartoti pažįstamus modelius, nes tai, ką darėte iki šiol, padėjo išlikti."],
      ["Streso kibiras", "Gebėjimą susidoroti su stresu galime įsivaizduoti kaip streso kibirą. Jį pildo realūs įvykiai: konfliktas, nepavykęs pristatymas, nemalonios naujienos, skausmas, liga, neapmokėta sąskaita ar per didelis krūvis. Jį pildo ir neigiamos mintys, nes pasąmonė ne visada skiria vaizduotę nuo realybės. Vieną nemalonų įvykį galime mintyse pakartoti dar dvidešimt ar trisdešimt kartų ir pradėti prognozuoti, kad jis vėl pasikartos. Kuo kibiras pilnesnis, tuo daugiau trukdžių kyla iš primityviojo proto: tampame negatyvesni, įkyresni ir budresni, kartojame elgesio modelius, kurie kibirą pildo dar labiau. Taip susidaro uždaras ratas. Mūsų darbo dalis — padėti jums kasdien dėti į kibirą mažiau: valdyti stresą, geriau spręsti problemas ir mąstyti naudingesniais būdais, taip pat veiksmingiau jį ištuštinti."],
      ["REM miegas ir atsipalaidavimas", "Natūralus streso kibiro ištuštinimo mechanizmas yra REM miegas. Tai miego etapas, kai sapnuojame. Sapnai padeda apdoroti dienos įvykius ir leidžia intelektualiajam protui iš naujo įvertinti jų emocinį krūvį. REM sudaro tik apie penktadalį miego ciklo, o patiriant didelį stresą miegas dažnai tampa ne toks veiksmingas. Atsipalaidavimo ir miego takelis gali padėti geriau pasinaudoti REM ritmu, pagerinti miego kokybę, atsipalaiduoti ir sustiprinti mūsų užsiėmimų metu atliekamą darbą. Jo nereikia klausytis išliekant budriems — galite įjungti prieš miegą, o pasąmonė jį girdės net jei greitai užmigsite."],
      ["Teigiamas elgesys ir rūpinimasis savimi", "Svarbu užsiimti teigiama veikla ir palaikyti ryšius, kurie suteikia gerą savijautą. Tokia veikla padeda organizmui gaminti neuromediatorius — serotoniną, dopaminą ir oksitociną — susijusius su ramybe, motyvacija, drąsa, laime ir ryšiu su kitais. Padeda mėgstama veikla, judėjimas, prasmingas bendravimas ir rūpinimasis savimi. Naudingos mintys taip pat gali keisti savijautą, nes smegenys stipriai reaguoja į tai, ką ryškiai įsivaizduojame."],
      ["Transas ir nauji pasirinkimai", "Transas yra natūrali būsena, panaši į svajojimą. Kiekvienas žmogus daug kartų per dieną į ją įeina ir iš jos išeina — pavyzdžiui, vairuodamas pažįstamu keliu ar trumpam pasinėręs į mintis. Tai sutelkto dėmesio būsena: nors atrodo, kad trumpam atsijungėme, smegenys intensyviai dirba. Seansų metu transas padeda atpalaiduoti nervų sistemą ir suteikia smegenims erdvės kurti naujas neuronines jungtis bei ieškoti sprendimų. Mintyse repetuojame tai, kaip norėtumėte jaustis ir elgtis, kad sustiprintume gebėjimą siekti tikslų. Keistis galima nekovojant su savimi — išgirstant savo kūno signalus ir suteikiant smegenims saugesnį naują pasirinkimą."],
    ],
  },
  en: {
    title: "Brain Explanation",
    open: "Open Brain Explanation in full screen", close: "Close", previous: "Previous", next: "Next", listen: "Listen", stop: "Stop", progress: "Explanation step", soundtrack: "Brain Explanation soundscape",
    intro: "I’d like to tell you a little about the brain and show how its different parts work together. Your brain is not fighting you — it is trying to protect you, sometimes using old information.",
    steps: [
      ["The intellectual brain", "The conscious part of your brain is the frontal cortex, where your awareness lives. We can call it the Boss or the CEO because it controls higher executive functions such as planning and organising, language comprehension, voluntary movement, self-control and emotional regulation. The frontal cortex is connected to the rest of the cortex — the subconscious part. While the conscious brain can process only a few pieces of information at one time, the subconscious can process millions. Together, the cortex is the intelligent, thinking part of the brain. It lets us think, rationalise, analyse, use language, recognise our own existence and plan for the future. It can make a reasonable, balanced assessment, focus on long-term goals and delay immediate gratification."],
      ["The primitive brain", "The primitive part of the mind is also known as the limbic system or the survival brain. It contains the amygdala — the brain’s health and safety officer. The amygdala continuously checks your environment to make sure you are safe and can activate fight, flight, freeze or fawn. It works with the hippocampus, an archive of experiences, habits and patterns. Experiences are filed with the emotional label they carried at the time. Emotions act like a very fast filing system, allowing the brain to react immediately when something resembles a previous source of pain or danger."],
      ["The alarm in the body", "When the hippocampus recognises danger, the amygdala activates protection and the hypothalamus — the pharmacist of the brain — releases cortisol and adrenaline. Your heart may beat faster, breathing can become rapid and shallow, and your skin may sweat. Blood moves toward the muscles, so you may feel dizzy, light-headed or notice your stomach churning. During this response the intellectual brain is temporarily hijacked by the primitive brain. This is ideal when facing real danger, but less helpful before an exam, an important meeting, a date or a speech. The protection system may recall old failures, predict a worst-case scenario or create physical symptoms to keep you away. It dislikes change and encourages familiar patterns because whatever you have done so far has kept you alive."],
      ["The stress bucket", "We can think of our capacity for stress as a stress bucket. It fills with real events: an argument, a presentation that went wrong, upsetting news, pain, illness, an unpaid bill or simply doing too much. Negative thoughts also create anxiety and fill the bucket because the subconscious does not always distinguish imagination from reality. One difficult event may be replayed twenty or thirty times, followed by predictions that it will happen again. The fuller the bucket, the more interference comes from the primitive mind. We become more negative, obsessive and vigilant, and repeat patterns that continue to top it up. This creates a negative loop. Part of our work is to help you put less into the bucket by managing stress, finding solutions and thinking in more helpful ways — and to help you empty it more efficiently."],
      ["REM sleep and relaxation", "We have a natural mechanism for emptying the bucket: REM sleep. This is the stage in which we dream. Dreams help us process the events of the day and allow the intellectual mind to reassess their emotional intensity. Only about twenty percent of the sleep cycle is REM, and sleep often becomes less effective when stress rises. A relaxation and sleep track can help you make better use of your REM pattern, improve the quality of sleep, relax and reinforce the work we do in sessions. You do not need to stay awake to listen — put it on when you are ready for sleep, and your subconscious can still hear it if you drift off."],
      ["Positive behaviour and self-care", "It is important to engage in positive activities and interactions that make you feel good. These behaviours help the body produce neurotransmitters such as serotonin, dopamine and oxytocin, associated with calm, motivation, courage, happiness and connection. Enjoyable interests, movement, meaningful social contact and self-care all help. Helpful thinking can also change how we feel because the brain responds powerfully to what we vividly imagine."],
      ["Trance and new choices", "Trance is a natural state similar to daydreaming. We all move in and out of trance many times a day — perhaps while driving a familiar route or becoming absorbed in thought. It is a state of focused awareness: although it can feel as if we briefly switch off, the brain is working hard. In sessions we use trance to relax the nervous system and give the brain space to form new neural connections and find solutions. We also use mental rehearsal to practise how you want to feel and respond, strengthening the ability to achieve your goals. You do not need to fight yourself to change — you can listen to your body’s signals and offer your brain a safer new choice."],
    ],
  },
  ru: {
    title: "Объяснение работы мозга",
    open: "Открыть объяснение работы мозга на весь экран", close: "Закрыть", previous: "Назад", next: "Далее", listen: "Слушать", stop: "Остановить", progress: "Шаг объяснения", soundtrack: "Звуковой фон объяснения работы мозга",
    intro: "Я хочу немного рассказать вам о мозге и показать, как его разные части работают вместе. Ваш мозг не борется с вами — он старается вас защитить, иногда опираясь на устаревшую информацию.",
    steps: [
      ["Интеллектуальный мозг", "Сознательная часть мозга — это лобная кора, где находится ваше осознание. Её можно назвать Боссом или Генеральным директором: она контролирует высшие исполнительные функции — планирование и организацию, понимание языка, произвольные движения, самоконтроль и эмоциональную регуляцию. Лобная кора связана с остальной корой — подсознательной частью. Сознательный мозг одновременно обрабатывает лишь несколько единиц информации, а подсознательная часть — миллионы. Вместе кора является разумной, мыслящей частью мозга. Она позволяет мыслить, анализировать, пользоваться языком, осознавать собственное существование и планировать будущее. Она способна дать взвешенную оценку, сосредоточиться на долгосрочной цели и отложить немедленное удовлетворение."],
      ["Примитивный мозг", "Примитивная часть мозга также называется лимбической системой или мозгом выживания. В ней находится миндалевидное тело — инспектор безопасности мозга. Оно постоянно оценивает окружающую среду и проверяет, находитесь ли вы в безопасности. При угрозе оно может включить реакции борьбы, бегства, замирания или подчинения. Ему помогает гиппокамп — архив переживаний, привычек и моделей поведения. Каждая ситуация сохраняется вместе с эмоциональной меткой. Эмоции работают как сверхбыстрая система поиска, поэтому мозг способен немедленно реагировать на то, что напоминает прежнюю боль или опасность."],
      ["Сигнал тревоги в теле", "Когда гиппокамп распознаёт опасность, миндалевидное тело включает защиту, а гипоталамус — фармацевт мозга — запускает выброс кортизола и адреналина. Сердце начинает биться быстрее, дыхание становится частым и поверхностным, кожа может вспотеть. Кровь направляется к мышцам, поэтому возможны головокружение, слабость или неприятные ощущения в животе. В этот момент интеллектуальный мозг временно уступает управление примитивному. Это идеальная реакция при реальной опасности, но она менее полезна перед экзаменом, важной встречей, свиданием или выступлением. Система защиты может напоминать о прошлых неудачах, предсказывать худший сценарий или создавать физические симптомы, чтобы удержать вас от ситуации. Она не любит перемен и побуждает повторять знакомые модели, потому что всё, что вы делали раньше, помогло вам выжить."],
      ["Ведро стресса", "Способность справляться со стрессом можно представить как ведро. Его наполняют реальные события: спор, неудачная презентация, неприятные новости, боль, болезнь, неоплаченный счёт или чрезмерная нагрузка. Негативные мысли тоже создают тревогу и наполняют ведро, потому что подсознание не всегда отличает воображение от реальности. Одно неприятное событие можно мысленно повторить двадцать или тридцать раз, а затем начать прогнозировать, что оно случится снова. Чем полнее ведро, тем больше вмешивается примитивный мозг. Мы становимся более негативными, навязчивыми и настороженными, повторяем модели, которые продолжают добавлять стресс. Так возникает замкнутый круг. Часть нашей работы — помочь вам меньше наполнять ведро: управлять стрессом, находить решения и мыслить более полезно, а также эффективнее его опустошать."],
      ["REM-сон и расслабление", "У нас есть естественный механизм опустошения ведра — фаза быстрого сна, или REM-сон. В этой стадии мы видим сны. Они помогают переработать события дня и позволяют интеллектуальному разуму заново оценить их эмоциональный уровень. На REM приходится около двадцати процентов цикла сна, а при сильном стрессе сон часто становится менее эффективным. Трек для расслабления и сна может помочь лучше использовать REM-ритм, улучшить качество сна, расслабиться и закрепить работу, которую мы выполняем на сеансах. Не нужно бодрствовать до конца — включите его перед сном, и подсознание продолжит слышать, даже если вы быстро заснёте."],
      ["Позитивное поведение и забота о себе", "Важно заниматься приятными делами и поддерживать взаимодействия, которые дают хорошее самочувствие. Такое поведение помогает организму вырабатывать нейромедиаторы — серотонин, дофамин и окситоцин, связанные со спокойствием, мотивацией, смелостью, счастьем и ощущением связи. Полезны любимые занятия, движение, значимое общение и забота о себе. Конструктивные мысли тоже могут менять самочувствие, потому что мозг глубоко реагирует на то, что мы ярко представляем."],
      ["Транс и новые выборы", "Транс — естественное состояние, похожее на мечтательность. Каждый человек много раз в день входит в него и выходит — например, за рулём на знакомой дороге или глубоко погрузившись в мысли. Это состояние сосредоточенного внимания: хотя кажется, что мы ненадолго отключились, мозг интенсивно работает. На сеансах транс помогает расслабить нервную систему и дать мозгу пространство для формирования новых нейронных связей и поиска решений. Мы также используем мысленную репетицию, чтобы практиковать желаемое состояние и поведение, укрепляя способность достигать целей. Чтобы измениться, не нужно бороться с собой — можно услышать сигналы тела и предложить мозгу новый, более безопасный выбор."],
    ],
  },
} as const;

export function BrainExperience({ lang }: { lang: Locale }) {
  const t = content[lang];
  const brainImage = lang === "ru" ? "/brain-explanation-ru.png" : lang === "en" ? "/brain-explanation-en.png" : "/brain-explanation-lt.png";
  const [open, setOpen] = useState(false);
  const [step, setStep] = useState(0);

  const stopSpeech = useCallback(() => {
    if (typeof window !== "undefined") window.speechSynthesis?.cancel();
  }, []);

  const close = useCallback(() => { stopSpeech(); setOpen(false); }, [stopSpeech]);
  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") close();
    };
    document.body.style.overflow = "hidden";
    window.addEventListener("keydown", onKey);
    return () => { document.body.style.overflow = ""; window.removeEventListener("keydown", onKey); window.speechSynthesis?.cancel(); };
  }, [close, open]);

  useEffect(() => {
    if (!open || !("speechSynthesis" in window)) return;
    const locale = lang === "lt" ? "lt-LT" : lang === "ru" ? "ru-RU" : "en-GB";
    const parts = [t.intro, ...t.steps.map(([title, text]) => `${title}. ${text}`)];
    let cancelled = false;
    const chooseVoice = () => {
      const language = locale.slice(0, 2).toLowerCase();
      const voices = window.speechSynthesis.getVoices().filter((voice) => voice.lang.toLowerCase().startsWith(language));
      const voice = voices.find((item) => /natural|google|microsoft|samantha|milena|anna/i.test(item.name)) ?? voices.find((item) => item.localService) ?? voices[0] ?? null;
      const speakPart = (index: number) => {
        if (cancelled || index >= parts.length) return;
        if (index > 0) setStep(index - 1);
        const utterance = new SpeechSynthesisUtterance(parts[index]);
        utterance.lang = locale;
        utterance.voice = voice;
        utterance.rate = .82;
        utterance.pitch = .96;
        utterance.volume = .9;
        utterance.onend = () => speakPart(index + 1);
        utterance.onerror = (event) => {
          if (!cancelled && event.error !== "canceled" && event.error !== "interrupted") speakPart(index + 1);
        };
        window.speechSynthesis.speak(utterance);
      };
      window.speechSynthesis.cancel();
      speakPart(0);
    };
    const voices = window.speechSynthesis.getVoices();
    if (voices.length) chooseVoice();
    else window.speechSynthesis.addEventListener("voiceschanged", chooseVoice, { once: true });
    return () => {
      cancelled = true;
      window.speechSynthesis.removeEventListener("voiceschanged", chooseVoice);
      window.speechSynthesis.cancel();
    };
  }, [lang, open, t.intro, t.steps]);

  return <>
    <button className="brain-open" type="button" onClick={() => setOpen(true)} aria-label={t.open}>
      <Image src={brainImage} alt={t.open} width={1256} height={1256} priority sizes="(max-width: 1050px) 82vw, 42vw" />
      <span><Play size={15} fill="currentColor" /> {t.title}</span>
    </button>
    {open && <section className="brain-story" role="dialog" aria-modal="true" aria-labelledby="brain-story-title">
      <button className="brain-story-close" type="button" onClick={close} aria-label={t.close}><X /><span>{t.close}</span></button>
      <div className="brain-story-visual" aria-hidden="true">
        <Image src={brainImage} alt="" fill sizes="55vw" />
        <div className={`brain-map brain-map-${Math.min(step, 5)}`}>
          <i className="map-intellect" /><i className="map-amygdala" /><i className="map-body" /><i className="map-bucket" /><i className="map-rest" /><i className="map-choice" />
        </div>
      </div>
      <div className="brain-story-copy">
        <p className="brain-story-kicker">{t.title} · {String(step + 1).padStart(2, "0")} / {String(t.steps.length).padStart(2, "0")}</p>
        <p className="brain-story-intro">{t.intro}</p>
        <YouTubeSoundscape videoId="UwUxyCj33PM" label={t.soundtrack} lang={lang} className="brain-youtube" />
        <h2 id="brain-story-title" key={`title-${step}`}>{t.steps[step][0]}</h2>
        <p className="brain-story-text" key={`text-${step}`}>{t.steps[step][1]}</p>
        <div className="brain-progress" aria-label={t.progress}>{t.steps.map((item, index) => <span key={item[0]} className={index === step ? "active" : ""} />)}</div>
      </div>
    </section>}
  </>;
}
