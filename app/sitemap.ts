import type { MetadataRoute } from "next";
import { publicSiteUrl } from "@/lib/site-config";

const routes = ["", "/en", "/ru", "/shop", "/en/shop", "/ru/shop"];

export default function sitemap(): MetadataRoute.Sitemap {
  return routes.map((route) => ({
    url: `${publicSiteUrl}${route}`,
    lastModified: new Date(),
    changeFrequency: route.includes("shop") ? "weekly" : "monthly",
    priority: route === "" ? 1 : route.includes("shop") ? 0.8 : 0.9,
  }));
}
