import { TherapySite } from "@/components/therapy-site";
import { makeHomeMetadata } from "@/lib/metadata";

export const metadata = makeHomeMetadata("en");

export default function Page() {
  return <TherapySite lang="en" />;
}
