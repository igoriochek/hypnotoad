import assert from "node:assert/strict";
import test from "node:test";

test("renders the Lithuanian landing page with complete navigation", async () => {
  const workerUrl = new URL("../dist/server/index.js", import.meta.url);
  workerUrl.searchParams.set("test", `${process.pid}-${Date.now()}`);
  const { default: worker } = await import(workerUrl.href);

  const response = await worker.fetch(
    new Request("http://localhost/", {
      headers: { accept: "text/html" },
    }),
    {
      ASSETS: {
        fetch: async () => new Response("Not found", { status: 404 }),
      },
    },
    {
      waitUntil() {},
      passThroughOnException() {},
    },
  );

  assert.equal(response.status, 200);
  assert.match(
    response.headers.get("content-type") ?? "",
    /^text\/html\b/i,
  );
  const html = await response.text();
  assert.match(html, /Oksana Sakalauskienė/);
  assert.match(html, /Kad pasikeistumėte, nereikia/);
  assert.match(html, /href=["']#smegenys["']/);
  assert.match(html, /href=["']#konsultacija["']/);
  assert.match(html, /href=["']\/shop["']/);
  assert.match(html, /href=["']\/en["']/);
  assert.match(html, /href=["']\/ru["']/);
  assert.match(html, /roxana71@protonmail\.com/);
  assert.match(html, /property=["']og:image["']/);
});
