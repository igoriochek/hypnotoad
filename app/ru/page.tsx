import { TherapySite } from "@/components/therapy-site";
import { makeHomeMetadata } from "@/lib/metadata";

export const metadata = makeHomeMetadata("ru");

export default function Page() {
  return <TherapySite lang="ru" />;
}
