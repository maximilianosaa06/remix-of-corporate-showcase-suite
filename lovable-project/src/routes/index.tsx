import { createFileRoute, Link } from "@tanstack/react-router";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { useSuspenseQuery, queryOptions } from "@tanstack/react-query";
import { listProyectos, listStaff, listNoticias, getContenidoSitio } from "@/lib/content.functions";
import { mediaUrl } from "@/lib/media";

import logoAsset from "@/assets/logo-sfl-color.png.asset.json";

const proyectosQuery = queryOptions({ queryKey: ["proyectos"], queryFn: () => listProyectos() });
const staffQuery = queryOptions({ queryKey: ["staff"], queryFn: () => listStaff() });
const noticiasQuery = queryOptions({ queryKey: ["noticias"], queryFn: () => listNoticias() });
const contenidoQuery = queryOptions({ queryKey: ["contenido-sitio"], queryFn: () => getContenidoSitio() });

export const Route = createFileRoute("/")({
  loader: ({ context }) => {
    context.queryClient.ensureQueryData(proyectosQuery);
    context.queryClient.ensureQueryData(staffQuery);
    context.queryClient.ensureQueryData(noticiasQuery);
    context.queryClient.ensureQueryData(contenidoQuery);
  },
  head: () => ({
    meta: [
      { title: "SFL ULS Lab — Software Factory Lab Universidad de La Serena" },
      {
        name: "description",
        content:
          "Software Factory Lab de la Universidad de La Serena: proyectos, servicios, staff y noticias del laboratorio de desarrollo de software.",
      },
      { property: "og:title", content: "SFL ULS Lab — Software Factory Lab" },
      {
        property: "og:description",
        content: "Proyectos, servicios y equipo del Software Factory Lab de la Universidad de La Serena.",
      },
    ],
  }),
  component: Index,
  errorComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-6xl px-4 py-16 text-center text-sm text-muted-foreground">
        No pudimos cargar el contenido. Intenta nuevamente más tarde.
      </p>
    </SiteLayout>
  ),
});

function Index() {
  const { data: proyectos } = useSuspenseQuery(proyectosQuery);
  const { data: staff } = useSuspenseQuery(staffQuery);
  const { data: noticias } = useSuspenseQuery(noticiasQuery);
  const { data: contenido } = useSuspenseQuery(contenidoQuery);

  return (
    <SiteLayout>
      <section className="border-b-2 border-brand bg-background text-foreground">
        <div className="mx-auto max-w-3xl px-5 py-14 text-center">
          <img src={logoAsset.url} alt="SFL ULS Lab" width={260} height={86} className="mx-auto h-20 w-auto" />
          <h1 className="mt-8 text-3xl font-extrabold sm:text-4xl">
            {contenido?.sobre_titulo ?? "Sobre nosotros"}
          </h1>
          <p className="mt-4 whitespace-pre-line text-sm leading-relaxed text-muted-foreground sm:text-base">
            {contenido?.sobre_texto ?? ""}
          </p>

          <h2 className="mt-10 text-2xl font-extrabold sm:text-3xl">
            {contenido?.mision_titulo ?? "Misión, visión y objetivos"}
          </h2>
          <p className="mt-4 whitespace-pre-line text-sm leading-relaxed text-muted-foreground sm:text-base">
            {contenido?.mision_texto ?? ""}
          </p>

          <Button asChild variant="destructive" className="mt-8">
            <Link to="/contacto">Contáctenos</Link>
          </Button>
        </div>
      </section>

      <section className="bg-surface py-12">
        <div className="mx-auto max-w-6xl px-4">
          <h2 className="text-center text-2xl font-bold">Proyectos de SFL</h2>
          <div className="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {proyectos.slice(0, 4).map((p) => (
              <article key={p.id} className="rounded-md bg-card card-shadow">
                <img
                  src={mediaUrl(p.imagen_url, "proyecto")}
                  alt={p.titulo}
                  loading="lazy"
                  width={800}
                  height={560}
                  className="aspect-[4/3] w-full rounded-t-md object-cover"
                />
                <div className="p-4">
                  <h3 className="text-base font-semibold">{p.titulo}</h3>
                  <p className="mt-2 text-sm text-muted-foreground">{p.descripcion}</p>
                  <Button asChild variant="destructive" size="sm" className="mt-4">
                    <Link to="/proyectos">Ver más</Link>
                  </Button>
                </div>
              </article>
            ))}
          </div>
          <div className="mt-8 text-center">
            <Button asChild variant="destructive">
              <Link to="/proyectos">Ver todos los proyectos</Link>
            </Button>
          </div>
        </div>
      </section>

      <section className="py-12">
        <div className="mx-auto max-w-6xl px-4">
          <div className="section-rule pt-8">
            <h2 className="text-center text-2xl font-bold">Conoce al Staff</h2>
          </div>
          <div className="mt-8 grid grid-cols-2 gap-6 lg:grid-cols-4">
            {staff.slice(0, 4).map((m) => (
              <article key={m.id}>
                <h3 className="text-sm font-semibold">{m.nombre}</h3>
                <img
                  src={mediaUrl(m.imagen_url, "staff")}
                  alt={m.nombre}
                  loading="lazy"
                  width={700}
                  height={700}
                  className="mt-2 aspect-square w-full rounded-md object-cover"
                />
                <p className="mt-2 text-sm font-semibold">{m.cargo}</p>
                <p className="text-xs text-muted-foreground">{m.descripcion}</p>
              </article>
            ))}
          </div>
          <div className="mt-8 text-center">
            <Button asChild variant="destructive">
              <Link to="/staff">Ver todos l@s miembr@s</Link>
            </Button>
          </div>
        </div>
      </section>

      <section className="bg-surface py-12">
        <div className="mx-auto max-w-6xl px-4">
          <div className="section-rule flex items-center justify-center gap-3 pt-8">
            <h2 className="text-2xl font-bold">Noticias</h2>
            <Button asChild variant="destructive" size="sm">
              <Link to="/noticias">Ver más</Link>
            </Button>
          </div>
          <div className="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {noticias.slice(0, 3).map((n) => (
              <article key={n.id} className="rounded-md bg-card card-shadow">
                <img
                  src={mediaUrl(n.imagen_url, "noticia")}
                  alt={n.titulo}
                  loading="lazy"
                  width={800}
                  height={560}
                  className="aspect-[16/9] w-full rounded-t-md object-cover"
                />
                <div className="p-4">
                  <h3 className="text-sm font-semibold">{n.titulo}</h3>
                  <p className="mt-2 text-xs text-muted-foreground">{n.resumen}</p>
                  <Link
                    to="/noticias/$noticiaId"
                    params={{ noticiaId: n.slug }}
                    className="mt-3 inline-block text-xs font-semibold text-primary underline"
                  >
                    Leer noticia
                  </Link>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>
    </SiteLayout>
  );
}
