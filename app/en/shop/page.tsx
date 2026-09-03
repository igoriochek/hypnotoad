import { ShopSite } from "@/components/shop-site";
import { makeShopMetadata } from "@/lib/metadata";

export const metadata = makeShopMetadata("en");

export default function Page() {
  return <ShopSite lang="en" />;
}
