import { TherapySite } from "@/components/therapy-site";
import { makeHomeMetadata } from "@/lib/metadata";

export const metadata = makeHomeMetadata("lt");

export default function Page() {
  return <TherapySite lang="lt" />;
}
