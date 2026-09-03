"use client";

import { FormEvent, useState } from "react";
import { Mail, MessageCircle, Send } from "lucide-react";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import type { Locale } from "@/lib/site-content";
import { contactEmail } from "@/lib/site-config";

const copy = {
  lt: { open: "Parašyti Oksanai", title: "Kuo galiu padėti?", description: "Trumpai parašykite, ko norėtumėte pasiekti. Žinutė bus perduota Oksanai el. paštu.", name: "Jūsų vardas", email: "Jūsų el. paštas", topic: "Pokalbio tema", topics: ["Nerimas ir stresas", "Miegas", "Baimė ar fobija", "Įpročio keitimas", "Kitas klausimas"], message: "Jūsų žinutė", send: "Siųsti Oksanai", privacy: "Žinutė neišsaugoma svetainėje. Paspaudus atsidarys jūsų el. pašto programa. Tai nėra skubios pagalbos kanalas.", subject: "Nauja kliento užklausa iš svetainės" },
  en: { open: "Message Oksana", title: "How can I help?", description: "Briefly tell Oksana what you would like to change. Your message will be handed over by email.", name: "Your name", email: "Your email", topic: "Topic", topics: ["Anxiety and stress", "Sleep", "Fear or phobia", "Changing a habit", "Another question"], message: "Your message", send: "Email Oksana", privacy: "The message is not stored on this website. Your email app will open. This is not an emergency support channel.", subject: "New client enquiry from the website" },
  ru: { open: "Написать Оксане", title: "Чем я могу помочь?", description: "Кратко напишите, каких изменений вы хотите. Сообщение будет передано Оксане по электронной почте.", name: "Ваше имя", email: "Ваш email", topic: "Тема", topics: ["Тревога и стресс", "Сон", "Страх или фобия", "Изменение привычки", "Другой вопрос"], message: "Ваше сообщение", send: "Отправить Оксане", privacy: "Сообщение не сохраняется на сайте. Откроется ваша почтовая программа. Это не канал экстренной помощи.", subject: "Новый запрос клиента с сайта" },
} as const;

export function ClientChat({ lang }: { lang: Locale }) {
  const t = copy[lang];
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [topic, setTopic] = useState(t.topics[0]);
  const [message, setMessage] = useState("");

  const submit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const body = [`${t.name}: ${name}`, `${t.email}: ${email}`, `${t.topic}: ${topic}`, "", message].join("\n");
    window.location.href = `mailto:${contactEmail}?subject=${encodeURIComponent(`${t.subject}: ${topic}`)}&body=${encodeURIComponent(body)}`;
  };

  return (
    <Dialog>
      <DialogTrigger asChild><button className="client-chat-trigger" type="button"><MessageCircle size={20} /><span>{t.open}</span></button></DialogTrigger>
      <DialogContent className="client-chat-dialog">
        <DialogHeader><DialogTitle>{t.title}</DialogTitle><DialogDescription>{t.description}</DialogDescription></DialogHeader>
        <form className="client-chat-form" onSubmit={submit}>
          <label><span>{t.name}</span><input value={name} onChange={(event) => setName(event.target.value)} required autoComplete="name" /></label>
          <label><span>{t.email}</span><input type="email" value={email} onChange={(event) => setEmail(event.target.value)} required autoComplete="email" /></label>
          <label><span>{t.topic}</span><select value={topic} onChange={(event) => setTopic(event.target.value)}>{t.topics.map((option) => <option key={option}>{option}</option>)}</select></label>
          <label><span>{t.message}</span><textarea value={message} onChange={(event) => setMessage(event.target.value)} required rows={5} /></label>
          <button type="submit"><Mail size={17} />{t.send}<Send size={16} /></button>
          <p>{t.privacy}</p>
        </form>
      </DialogContent>
    </Dialog>
  );
}
