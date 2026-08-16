import { createFileRoute } from "@tanstack/react-router";
import { useSuspenseQuery, queryOptions } from "@tanstack/react-query";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { listProyectos } from "@/lib/content.functions";
import { mediaUrl } from "@/lib/media";

const proyectosQuery = queryOptions({
  queryKey: ["proyectos"],
  queryFn: () => listProyectos(),
});

export const Route = createFileRoute("/proyectos")({
  loader: ({ context }) => {
    context.queryClient.ensureQueryData(proyectosQuery);
  },
  head: () => ({
    meta: [
      { title: "Proyectos y Servicios — SFL ULS Lab" },
      {
        name: "description",
        content:
          "Conoce los proyectos y servicios desarrollados por el Software Factory Lab de la Universidad de La Serena.",
      },
      { property: "og:title", content: "Proyectos y Servicios — SFL ULS Lab" },
      {
        property: "og:description",
        content: "Plataformas, aplicaciones y servicios digitales desarrollados por SFL ULS Lab.",
      },
    ],
  }),
  component: ProyectosPage,
  errorComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-6xl px-4 py-16 text-center text-sm text-muted-foreground">
        No pudimos cargar los proyectos. Intenta nuevamente más tarde.
      </p>
    </SiteLayout>
  ),
});

function ProyectosPage() {
  const { data: proyectos } = useSuspenseQuery(proyectosQuery);

  return (
    <SiteLayout>
      <div className="mx-auto max-w-6xl px-4 py-10">
        <h1 className="text-center text-3xl font-extrabold">Proyectos / Servicios</h1>

        <div className="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          {proyectos.map((p) => (
            <article key={p.id}>
              <h2 className="text-sm font-semibold">{p.titulo}</h2>
              <img
                src={mediaUrl(p.imagen_url, "proyecto")}
                alt={p.titulo}
                loading="lazy"
                width={800}
                height={560}
                className="mt-2 aspect-[4/3] w-full rounded-md object-cover"
              />
              <p className="mt-3 text-sm text-muted-foreground">{p.descripcion}</p>
            </article>
          ))}
        </div>

        {proyectos.length === 0 ? (
          <p className="mt-10 text-center text-sm text-muted-foreground">
            Aún no hay proyectos publicados.
          </p>
        ) : null}
      </div>
    </SiteLayout>
  );
}
