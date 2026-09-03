# Programuotojo instrukcija: shkelio.com

## 1. Perduodamas rezultatas

Projektas yra veikianti trikalbė Oksanos Sakalauskienės klinikinės hipnoterapijos svetainė. Perduodamas visas šaltinio kodas ir visos naudotos medijos. Nieko iš sukurtos svetainės nepašalinta.

Esama vieša versija, kurią galima naudoti kaip funkcinį ir vizualinį etaloną:

`https://oksana-inspiraology.jolana-sakal-5998.chatgpt.site`

Tikslinis pagrindinis domenas:

`https://shkelio.com`

Galutinis hostingas dar neparinktas. Jį turi parinkti, paruošti ir administruoti svetainę perimantys programuotojai.

Technologijos:

- React 19 ir Next.js 16 komponentų modelis;
- Vinext / Vite build;
- Cloudflare Worker suderinamas serverio rezultatas;
- TypeScript;
- CSS ir Tailwind 4 bazė;
- papildoma D1 / Drizzle struktūra, kurią galima panaudoti užsakymams;
- duomenų bazė šiuo metu nenaudojama.

## 2. Svetainės funkcijos

- LT / EN / RU turinys ir kalbų perjungimas;
- pilna kompiuterio ir telefono navigacija;
- interaktyvus „Brain Explanation“ visomis trimis kalbomis;
- subtilus generuojamas foninis garsas su išjungimo mygtuku;
- nemokamas „Sleep Track“ garso įrašas;
- muzikos ir dažnių rekomendacijų nuorodos;
- konsultacijų temų interaktyvūs paaiškinimai;
- metodikos, konsultacijos eigos, specialistės ir DUK skyriai;
- telefono bei el. pašto kontaktai;
- kliento žinutės forma, kuri dabar atidaro el. pašto programą;
- parduotuvė visomis trimis kalbomis;
- krepšelis su kiekiu ir pasirinkimų pašalinimu;
- dabartinė užsakymo funkcija parengia el. laišką, bet nepriima pinigų;
- SEO metadata, `robots.txt`, `sitemap.xml`, `hreflang` ir Schema.org duomenys.

## 3. Kur ką keisti

| Paskirtis | Failas / aplankas |
| --- | --- |
| Visi LT / EN / RU pagrindinio puslapio ir parduotuvės tekstai | `lib/site-content.ts` |
| Domenas, el. paštas ir telefonas | `lib/site-config.ts` |
| Pagrindinis puslapis ir jo sekcijų tvarka | `components/therapy-site.tsx` |
| Navigacija ir mobilusis meniu | `components/site-header.tsx` |
| Parduotuvės puslapis | `components/shop-site.tsx` |
| Krepšelio logika | `components/shop-catalog.tsx` |
| Kontaktinė forma | `components/client-chat.tsx` |
| Brain Explanation | `components/brain-experience.tsx` |
| Sleep Track | `components/sleep-track.tsx` |
| Foninio garso sintezė | `components/ambient-sound.tsx` |
| Spalvos, tipografija, responsive dizainas | `app/globals.css` |
| Paveikslai, logotipas ir garso įrašas | `public/` |
| SEO nustatymai | `app/layout.tsx`, `lib/metadata.ts`, `app/sitemap.ts`, `app/robots.ts` |

## 4. Hostingo parinkimas ir domeno shkelio.com prijungimas

### Reikalavimai hostingui

Programuotojai turi parinkti hostingą, kuris palaiko dabartinį Vinext / Cloudflare Worker build arba adaptuoti projektą pasirinktam Node.js hostingui. Reikalinga:

- produkcinis HTTPS;
- aplinkos kintamieji ir saugios secrets;
- serverio maršrutai mokėjimų API ir webhook;
- duomenų bazė užsakymams;
- logai ir klaidų stebėjimas;
- atsarginės kopijos;
- galimybė prijungti `shkelio.com` ir `www.shkelio.com`.

### Domeno prijungimo tvarka

1. Paruošti ir patikrinti produkcinį hostingą laikinu adresu.
2. Hostinge nustatyti:

   `NEXT_PUBLIC_SITE_URL=https://shkelio.com`

3. Hostingo valdyme pridėti `shkelio.com` kaip pagrindinį domeną.
4. Domeno registratoriaus DNS lange įrašyti **tik tas A, AAAA arba CNAME reikšmes, kurias pateikia pasirinktas hostingas**.
5. `www.shkelio.com` nukreipti 301 peradresavimu į `https://shkelio.com` arba pridėti kaip papildomą domeną ir nustatyti canonical į apex domeną.
6. Įjungti ir patikrinti automatinį HTTPS sertifikatą.
7. Visus HTTP adresus 301 nukreipti į HTTPS.
8. Po domeno prijungimo iš naujo sukurti produkcinį build, kad canonical, Open Graph, sitemap ir absoliučios nuorodos naudotų `shkelio.com`.
9. Google Search Console pridėti domeno nuosavybę ir pateikti `https://shkelio.com/sitemap.xml`.

Keičiant DNS negalima aklai pakeisti visų vardų serverių, jei tame pačiame domene veiks el. paštas. Prieš pakeitimą reikia išsaugoti MX, SPF, DKIM ir DMARC įrašus.

## 5. Dabartinė parduotuvės būklė

Šiuo metu produktai, kainos, trys kalbos, krepšelis ir el. paštu siunčiama užklausa veikia naršyklėje. Mokėjimas nevykdomas, užsakymas duomenų bazėje nesaugomas, sąskaita ir patvirtinimo laiškas negeneruojami.

Perduodamos kainos:

| Vidinis kodas | Produktas | Kaina | Mokėjimo tipas |
| --- | --- | ---: | --- |
| `consultation_single` | Individuali konsultacija | 132,00 EUR | Internetinis mokėjimas |
| `phobia_program_4w` | 4 savaičių darbas su baime ar fobija | 600,00 EUR | Internetinis mokėjimas arba pradinis mokėjimas pagal verslo sprendimą |
| `smoking_cessation` | Metimo rūkyti konsultacija | 520,00 EUR | Internetinis mokėjimas |
| `book_science_change` | Knyga „Mokslu grįstas pokytis“ | 30,00 EUR | Internetinis mokėjimas + pristatymas |
| `group_training_matthew` | Mokymai grupėms su Matthew Cahill | Kaina derinama | Tik užklausa, nedėti į mokamą Checkout |

Kainos mokėjimų kode turi būti saugomos sveikais euro centais: `13200`, `60000`, `52000`, `3000`. Negalima pasitikėti kaina, kurią į serverį atsiunčia naršyklė.

## 6. Rekomenduojama mokėjimo architektūra

Baltijos bankiniams mokėjimams galima integruoti Montonio, Paysera Checkout arba MakeCommerce. Galutinį teikėją pasirenka svetainės savininkė, sudariusi sutartį. Rekomenduojama naudoti teikėjo talpinamą Checkout puslapį, kad svetainė niekada negautų ir nesaugotų kortelės duomenų.

### Privalomas srautas

1. Pirkėjas krepšelyje paspaudžia „Mokėti“.
2. Naršyklė siunčia serveriui tik produktų kodus, kiekius, kalbą ir būtiną kliento informaciją.
3. Serveris pats suranda produktus ir kainas patikimame kataloge, perskaičiuoja sumą ir sugeneruoja unikalų užsakymo numerį.
4. Duomenų bazėje sukuriamas `pending` užsakymas.
5. Serveris sukuria mokėjimą pasirinkto teikėjo API ir gauna saugų peradresavimo adresą.
6. Naršyklė nukreipiama į mokėjimo teikėjo Checkout.
7. Mokėjimo teikėjas siunčia serveriui pasirašytą webhook.
8. Serveris patikrina parašą, užsakymo numerį, valiutą, sumą ir mokėjimo būseną. Webhook turi būti idempotentinis.
9. Tik patvirtintas webhook pakeičia užsakymą į `paid`. Sėkmės URL ar vartotojo naršyklės parametrai nėra mokėjimo įrodymas.
10. Pirkėjui ir Oksanai išsiunčiamas patvirtinimas. Knygos užsakymui perduodama pristatymo informacija.

### Siūlomi serverio maršrutai

- `POST /api/checkout` — validuoja krepšelį ir sukuria mokėjimą;
- `POST /api/payments/webhook` — tikrina teikėjo parašą ir atnaujina užsakymą;
- `GET /order/success?order=...` — rodo tik serveryje patikrintą būseną;
- `GET /order/cancelled?order=...` — rodo atšauktą arba nebaigtą mokėjimą.

### Rekomenduojamos užsakymo būsenos

`pending`, `payment_started`, `paid`, `cancelled`, `failed`, `refunded`, `fulfilled`.

### Slapti nustatymai

Tikros API reikšmės turi būti hostingo secrets, o ne `.env.example`, Git ar naršyklės kode:

- `PAYMENT_PROVIDER`;
- `PAYMENT_API_URL`;
- `PAYMENT_ACCESS_KEY`;
- `PAYMENT_SECRET_KEY`;
- `PAYMENT_WEBHOOK_SECRET`;
- `ORDER_NOTIFICATION_EMAIL`.

Prieš gamybinį paleidimą būtina atlikti teikėjo Sandbox mokėjimą, nesėkmingą mokėjimą, pakartotinį webhook, neteisingo parašo testą, sumos neatitikimo testą ir grąžinimo scenarijų.

Oficialūs dokumentai:

- Montonio Payments API: `https://docs.montonio.com/api/payments`
- Paysera Checkout webhooks: `https://developers.paysera.com/guides/checkout-modern/api-integration/webhooks`
- MakeCommerce: `https://makecommerce.net/`

## 7. Duomenų bazė

Projekte jau yra D1 / Drizzle paruošimas, bet schema tuščia. Minimaliai reikia lentelių:

- `orders`: id, order_number, locale, customer_email, customer_name, status, currency, total_cents, provider, provider_payment_id, created_at, updated_at;
- `order_items`: id, order_id, product_code, title_snapshot, unit_price_cents, quantity;
- `payment_events`: id, provider_event_id, order_id, event_type, verified, received_at;
- knygos pristatymui — atskiri pristatymo laukai arba saugi integracija su siuntų sistema.

Mokėjimo įvykiams būtinas unikalus `provider_event_id`, kad tas pats webhook nebūtų įvykdytas du kartus.

## 8. Teisiniai ir privatumo darbai prieš mokėjimų paleidimą

Prieš priimant pinigus turi būti patvirtinti ir svetainėje aiškiai pasiekiami:

- privatumo politika;
- paslaugų ir pirkimo taisyklės;
- atšaukimo, pinigų grąžinimo ir konsultacijos perkėlimo sąlygos;
- knygos pristatymo terminai, kaina ir grąžinimo taisyklės;
- pardavėjo juridinis vardas, veiklos forma, adresas ir kiti privalomi rekvizitai;
- nuoroda, kad hipnoterapija nepakeičia skubios ar medicininės pagalbos;
- slapukų pasirinkimas, jei bus naudojama reklaminė analitika ar trečiųjų šalių stebėjimas.

Kontaktinė forma dabar duomenų nesaugo — ji atidaro lankytojo el. pašto programą. Jei forma bus pakeista serverine, reikia rinkti tik būtinus duomenis, pateikti privatumo informaciją ir nenustatyti atviro medicininių duomenų lauko be aiškaus teisinio pagrindo bei apsaugos.

## 9. Medijos ir trečiųjų šalių turinys

`public/` kataloge yra visi dabartinės svetainės paveikslai, logotipas ir „Sleep Track“. Prieš komercinį paleidimą savininkė ir programuotojas turi patvirtinti, kad yra teisė naudoti kiekvieną pateiktą mediją.

YouTube nuorodos turi būti peržiūrėtos dėl prieinamumo, autoriaus teisių ir slapukų. Jei įterpiamas YouTube grotuvas, rekomenduojamas privacy-enhanced režimas ir sutikimo valdymas, kai jo reikalauja pasirinkta analitika / reklamos konfigūracija.

## 10. Diegimo patikra

Prieš perdavimą į produkciją:

- `npm ci`;
- `npm run lint`;
- `npm run build`;
- `npm test`;
- patikrinti `/`, `/en`, `/ru`, `/shop`, `/en/shop`, `/ru/shop`;
- patikrinti pilną navigaciją kompiuteryje ir telefone;
- patikrinti kalbos keitimą kiekviename pagrindiniame ir parduotuvės puslapyje;
- patikrinti Brain Explanation atidarymą, klaviatūros valdymą ir uždarymą;
- patikrinti „Sleep Track“, foninio garso išjungimą ir `prefers-reduced-motion`;
- patikrinti telefono bei el. pašto nuorodas;
- patikrinti `robots.txt`, `sitemap.xml`, canonical ir hreflang;
- patikrinti 404 puslapį;
- atlikti Lighthouse prieinamumo ir našumo patikrą;
- prieš įjungiant mokėjimus atlikti visą Sandbox scenarijų.

## 11. Ko negalima daryti

- Nelaikyti kortelės duomenų svetainėje ar duomenų bazėje.
- Neįrašyti API raktų į naršyklės kodą, Git ar perduodamą archyvą.
- Nepasitikėti krepšelio suma iš kliento pusės.
- Nežymėti užsakymo apmokėtu pagal vien sėkmės URL.
- Nekurti medicininių rezultatų garantijų ar nepagrįstų gydymo teiginių.
- Neišjungti esamų saugių formuluočių ir sveikatos perspėjimų be profesinės bei teisinės peržiūros.
