import { createFileRoute, Link, notFound } from "@tanstack/react-router";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { getNoticia, listNoticias } from "@/lib/content.functions";
import { mediaUrl } from "@/lib/media";

export const Route = createFileRoute("/noticias/$noticiaId")({
  loader: async ({ params }) => {
    const [noticia, todas] = await Promise.all([
      getNoticia({ data: { slug: params.noticiaId } }),
      listNoticias(),
    ]);
    if (!noticia) throw notFound();
    return { noticia, otras: todas.filter((n: { slug: string }) => n.slug !== noticia.slug).slice(0, 2) };
  },
  head: ({ loaderData }) => {
    if (!loaderData) {
      return {
        meta: [{ title: "Noticia no disponible — SFL ULS Lab" }, { name: "robots", content: "noindex" }],
      };
    }
    const { noticia } = loaderData;
    return {
      meta: [
        { title: `${noticia.titulo} — SFL ULS Lab` },
        { name: "description", content: noticia.resumen },
        { property: "og:title", content: noticia.titulo },
        { property: "og:description", content: noticia.resumen },
      ],
    };
  },
  component: NoticiaPage,
  errorComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-3xl px-4 py-16 text-center text-sm text-muted-foreground">
        No pudimos cargar esta noticia. Intenta nuevamente más tarde.
      </p>
    </SiteLayout>
  ),
  notFoundComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-3xl px-4 py-16 text-center text-sm text-muted-foreground">
        Esta noticia no existe o fue despublicada.
      </p>
    </SiteLayout>
  ),
});

function NoticiaPage() {
  const { noticia, otras } = Route.useLoaderData();
  const parrafos = noticia.cuerpo.split("\n").filter((p: string) => p.trim().length > 0);

  return (
    <SiteLayout>
      <article className="mx-auto max-w-3xl px-4 py-10">
        <h1 className="text-center text-3xl font-extrabold uppercase">{noticia.titulo}</h1>
        <p className="mt-4 text-sm italic text-muted-foreground">Redactor: {noticia.redactor}</p>

        <p className="mt-6 text-sm leading-relaxed">{noticia.resumen}</p>
        <img
          src={mediaUrl(noticia.imagen_url, "noticia")}
          alt={noticia.titulo}
          loading="lazy"
          width={800}
          height={560}
          className="mx-auto mt-6 w-full max-w-md rounded-md object-cover"
        />
        {parrafos.map((parrafo: string, i: number) => (
          <p key={`${i}-${parrafo.slice(0, 16)}`} className="mt-5 text-sm leading-relaxed">
            {parrafo}
          </p>
        ))}
      </article>

      <section className="mx-auto max-w-3xl px-4 pb-14">
        <div className="section-rule pt-6">
          <h2 className="text-lg font-semibold">Otras noticias relevantes</h2>
        </div>
        <div className="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
          {otras.map((n: { id: string; slug: string; titulo: string; redactor: string; resumen: string; imagen_url: string | null }) => (
            <article key={n.id}>
              <img
                src={mediaUrl(n.imagen_url, "noticia")}
                alt={n.titulo}
                loading="lazy"
                width={800}
                height={560}
                className="aspect-[16/10] w-full rounded-md object-cover"
              />
              <h3 className="mt-3 text-sm font-semibold">{n.titulo}</h3>
              <p className="text-xs text-muted-foreground">por: {n.redactor}</p>
              <p className="mt-2 text-xs text-muted-foreground">{n.resumen}</p>
              <Button asChild variant="destructive" size="sm" className="mt-3">
                <Link to="/noticias/$noticiaId" params={{ noticiaId: n.slug }}>
                  Ver más
                </Link>
              </Button>
            </article>
          ))}
        </div>
      </section>
    </SiteLayout>
  );
}
