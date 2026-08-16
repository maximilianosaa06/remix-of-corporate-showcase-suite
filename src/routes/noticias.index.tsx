import { createFileRoute, Link } from "@tanstack/react-router";
import { useSuspenseQuery, queryOptions } from "@tanstack/react-query";
import { Search } from "lucide-react";
import { useMemo, useState } from "react";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { listNoticias } from "@/lib/content.functions";
import { mediaUrl } from "@/lib/media";

const noticiasQuery = queryOptions({ queryKey: ["noticias"], queryFn: () => listNoticias() });

export const Route = createFileRoute("/noticias/")({
  loader: ({ context }) => {
    context.queryClient.ensureQueryData(noticiasQuery);
  },
  head: () => ({
    meta: [
      { title: "Noticias — SFL ULS Lab" },
      {
        name: "description",
        content:
          "Últimas noticias, convocatorias y actividades del Software Factory Lab de la Universidad de La Serena.",
      },
      { property: "og:title", content: "Noticias — SFL ULS Lab" },
      {
        property: "og:description",
        content: "Novedades, convenios y actividades del laboratorio SFL ULS Lab.",
      },
    ],
  }),
  component: NoticiasPage,
  errorComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-6xl px-4 py-16 text-center text-sm text-muted-foreground">
        No pudimos cargar las noticias. Intenta nuevamente más tarde.
      </p>
    </SiteLayout>
  ),
});

function NoticiasPage() {
  const { data: noticias } = useSuspenseQuery(noticiasQuery);
  const [query, setQuery] = useState("");

  const resultados = useMemo(() => {
    const q = query.trim().toLowerCase();
    if (!q) return noticias;
    return noticias.filter(
      (n) => n.titulo.toLowerCase().includes(q) || n.resumen.toLowerCase().includes(q),
    );
  }, [query, noticias]);

  return (
    <SiteLayout>
      <div className="mx-auto max-w-6xl px-4 py-8">
        <div className="relative">
          <Search className="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input
            value={query}
            onChange={(e) => setQuery(e.target.value.slice(0, 100))}
            placeholder="Buscar"
            aria-label="Buscar noticias"
            maxLength={100}
            className="pl-9"
          />
        </div>

        <div className="section-rule mt-8 pt-6">
          <h1 className="text-xl font-bold">Noticias más recientes</h1>
        </div>

        <div className="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          {resultados.map((n) => (
            <article key={n.id}>
              <img
                src={mediaUrl(n.imagen_url, "noticia")}
                alt={n.titulo}
                loading="lazy"
                width={800}
                height={560}
                className="aspect-[16/10] w-full rounded-md object-cover"
              />
              <h2 className="mt-3 text-base font-semibold">{n.titulo}</h2>
              <p className="text-xs text-muted-foreground">por: {n.redactor}</p>
              <p className="mt-2 text-sm text-muted-foreground">{n.resumen}</p>
              <Button asChild variant="destructive" size="sm" className="mt-3">
                <Link to="/noticias/$noticiaId" params={{ noticiaId: n.slug }}>
                  Ver más
                </Link>
              </Button>
            </article>
          ))}
        </div>

        {resultados.length === 0 ? (
          <p className="mt-10 text-center text-sm text-muted-foreground">
            No se encontraron noticias para tu búsqueda.
          </p>
        ) : (
          <p className="mt-10 text-center text-xs text-muted-foreground">
            mostrando {resultados.length} de {noticias.length} resultados
          </p>
        )}
      </div>
    </SiteLayout>
  );
}
