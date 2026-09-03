# shkelio.com — Oksana Sakalauskienė

Pilnas trikalbės klinikinės hipnoterapijos svetainės projektas. Svetainėje yra pagrindinis pristatymas, interaktyvus „Brain Explanation“, konsultacijų kryptys, metodo ir konsultacijos paaiškinimai, nemokamas „Sleep Track“, muzikos nuorodos, DUK, kontaktinė forma, paslaugų parduotuvė ir krepšelis.

## Kalbos ir maršrutai

| Kalba | Pagrindinis puslapis | Parduotuvė |
| --- | --- | --- |
| Lietuvių | `/` | `/shop` |
| English | `/en` | `/en/shop` |
| Русский | `/ru` | `/ru/shop` |

## Paleidimas programuotojui

Reikalavimai: Node.js `>=22.13.0`, npm ir Linux / macOS aplinka.

```bash
npm ci
cp .env.example .env.local
npm run dev
```

Patikrinimas prieš diegimą:

```bash
npm run lint
npm run build
npm test
```

Pagrindiniai svetainės nustatymai yra `lib/site-config.ts`, tekstai visomis kalbomis — `lib/site-content.ts`, bendras dizainas — `app/globals.css`, o medijos failai — `public/`.

## Hostingas ir domenas

Galutinis hostingas dar neparinktas. Programuotojai turi parinkti Node.js / Cloudflare Worker suderinamą hostingą, įdiegti projektą, prijungti `shkelio.com`, sukonfigūruoti HTTPS ir produkcijoje nustatyti:

```text
NEXT_PUBLIC_SITE_URL=https://shkelio.com
```

## Svarbu apie parduotuvę

Dabartinė parduotuvė ir krepšelis yra pilnai perduodami, tačiau tai dar yra **užklausos el. paštu**, o ne bankinio apmokėjimo sistema. Prieš priimant realius mokėjimus programuotojas turi įgyvendinti serverinį užsakymą, mokėjimo paslaugų teikėjo Checkout, pasirašytą webhook, užsakymo būsenas ir teisines parduotuvės skiltis.

Visa perdavimo ir mokėjimų integravimo instrukcija: [docs/PROGRAMUOTOJO-INSTRUKCIJA.md](docs/PROGRAMUOTOJO-INSTRUKCIJA.md).
