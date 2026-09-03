import { ShopSite } from "@/components/shop-site";
import { makeShopMetadata } from "@/lib/metadata";

export const metadata = makeShopMetadata("lt");

export default function Page() {
  return <ShopSite lang="lt" />;
}
