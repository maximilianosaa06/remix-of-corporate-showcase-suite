import { createFileRoute } from "@tanstack/react-router";
import { useSuspenseQuery, queryOptions } from "@tanstack/react-query";
import { SiteLayout } from "@/components/site/SiteLayout";
import { listStaff } from "@/lib/content.functions";
import { mediaUrl } from "@/lib/media";

const staffQuery = queryOptions({ queryKey: ["staff"], queryFn: () => listStaff() });

export const Route = createFileRoute("/staff")({
  loader: ({ context }) => {
    context.queryClient.ensureQueryData(staffQuery);
  },
  head: () => ({
    meta: [
      { title: "Miembros del Staff — SFL ULS Lab" },
      {
        name: "description",
        content:
          "Equipo del Software Factory Lab de la Universidad de La Serena: roles, cargos y áreas de trabajo.",
      },
      { property: "og:title", content: "Miembros del Staff — SFL ULS Lab" },
      {
        property: "og:description",
        content: "Conoce al equipo profesional y estudiantil detrás de los proyectos de SFL ULS Lab.",
      },
    ],
  }),
  component: StaffPage,
  errorComponent: () => (
    <SiteLayout>
      <p className="mx-auto max-w-6xl px-4 py-16 text-center text-sm text-muted-foreground">
        No pudimos cargar el staff. Intenta nuevamente más tarde.
      </p>
    </SiteLayout>
  ),
});

function StaffPage() {
  const { data: staff } = useSuspenseQuery(staffQuery);

  return (
    <SiteLayout>
      <div className="mx-auto max-w-6xl px-4 py-10">
        <h1 className="text-center text-3xl font-extrabold">Miembros del Staff</h1>

        <div className="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          {staff.map((m) => (
            <article key={m.id}>
              <h2 className="text-sm font-semibold">{m.nombre}</h2>
              <img
                src={mediaUrl(m.imagen_url, "staff")}
                alt={m.nombre}
                loading="lazy"
                width={700}
                height={700}
                className="mt-2 aspect-square w-full rounded-md object-cover"
              />
              <p className="mt-3 text-sm font-semibold">{m.cargo}</p>
              <p className="mt-1 text-sm text-muted-foreground">{m.descripcion}</p>
            </article>
          ))}
        </div>

        {staff.length === 0 ? (
          <p className="mt-10 text-center text-sm text-muted-foreground">
            Aún no hay miembros publicados.
          </p>
        ) : null}
      </div>
    </SiteLayout>
  );
}
