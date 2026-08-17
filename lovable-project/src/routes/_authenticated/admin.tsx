import { createFileRoute, useNavigate } from "@tanstack/react-router";
import { useQuery, useQueryClient } from "@tanstack/react-query";
import { useState } from "react";
import { supabase } from "@/integrations/supabase/client";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { RecordManager } from "@/components/admin/RecordManager";
import { NoticiasManager } from "@/components/admin/NoticiasManager";
import { ContenidoEditor } from "@/components/admin/ContenidoEditor";
import { UsuariosManager } from "@/components/admin/UsuariosManager";

export const Route = createFileRoute("/_authenticated/admin")({
  head: () => ({
    meta: [
      { title: "Panel de administración — SFL ULS Lab" },
      {
        name: "description",
        content: "Gestión de proyectos, staff y noticias del Software Factory Lab ULS.",
      },
      { property: "og:title", content: "Panel de administración — SFL ULS Lab" },
      { property: "og:description", content: "Administra el contenido del sitio SFL ULS Lab." },
      { name: "robots", content: "noindex" },
    ],
  }),
  component: AdminPage,
});

type TabId = "proyectos" | "staff" | "noticias" | "contenido" | "footer" | "usuarios";

function AdminPage() {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const [tab, setTab] = useState<TabId | null>(null);

  const sesionQuery = useQuery({
    queryKey: ["mis-roles"],
    queryFn: async () => {
      const { data: userData } = await supabase.auth.getUser();
      const userId = userData.user?.id ?? "";
      if (!userId) return { userId: "", roles: [] as string[] };
      const { data, error } = await supabase.from("user_roles").select("role").eq("user_id", userId);
      if (error) throw new Error(error.message);
      return { userId, roles: (data ?? []).map((r) => String(r.role)) };
    },
  });

  const roles = sesionQuery.data?.roles ?? [];
  const userId = sesionQuery.data?.userId ?? "";
  const esAdmin = roles.includes("admin");
  const esEditor = roles.includes("editor");
  const esRedactor = roles.includes("redactor");

  const tabs: { id: TabId; label: string }[] = [
    ...(esAdmin ? ([{ id: "proyectos", label: "Proyectos" }] as const) : []),
    ...(esAdmin ? ([{ id: "staff", label: "Staff" }] as const) : []),
    ...(esAdmin || esEditor || esRedactor
      ? ([{ id: "noticias", label: "Noticias" }] as const)
      : []),
    ...(esAdmin ? ([{ id: "contenido", label: "Sobre nosotros" }] as const) : []),
    ...(esAdmin ? ([{ id: "footer", label: "Footer" }] as const) : []),
    ...(esAdmin ? ([{ id: "usuarios", label: "Usuarios y roles" }] as const) : []),
  ];

  const activo = tab ?? tabs[0]?.id ?? null;

  const signOut = async () => {
    await queryClient.cancelQueries();
    queryClient.clear();
    await supabase.auth.signOut();
    navigate({ to: "/login", replace: true });
  };

  return (
    <SiteLayout>
      <div className="mx-auto max-w-6xl px-4 py-10">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <h1 className="text-2xl font-extrabold">Panel de administración</h1>
          <Button variant="outline" size="sm" onClick={signOut}>
            Cerrar sesión
          </Button>
        </div>

        {sesionQuery.isLoading ? (
          <p className="mt-8 text-sm text-muted-foreground">Verificando permisos…</p>
        ) : tabs.length === 0 ? (
          <div className="mt-8 rounded-md bg-panel p-6 card-shadow">
            <h2 className="text-base font-semibold">Sin permisos de edición</h2>
            <p className="mt-2 text-sm text-muted-foreground">
              Tu cuenta está activa pero no tiene un rol con permisos de gestión. Pide a un
              administrador que te asigne uno.
            </p>
          </div>
        ) : (
          <>
            <div className="mt-6 grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
              {tabs.map((t) => (
                <Button
                  key={t.id}
                  size="sm"
                  className="w-full min-w-0 sm:w-auto"
                  variant={activo === t.id ? "destructive" : "outline"}
                  onClick={() => setTab(t.id)}
                >
                  <span className="truncate">{t.label}</span>
                </Button>
              ))}
            </div>

            <div className="mt-8">
              {activo === "proyectos" ? (
                <RecordManager
                  tabla="proyectos"
                  titulo="Proyectos y servicios"
                  titleField="titulo"
                  tipoImagen="proyecto"
                  orderBy={{ column: "orden", ascending: true }}
                  defaults={{ titulo: "", descripcion: "", imagen_url: null, orden: 0 }}
                  campos={[
                    { name: "titulo", label: "Título", type: "text", required: true, maxLength: 120 },
                    { name: "descripcion", label: "Descripción", type: "textarea", maxLength: 600 },
                    { name: "imagen_url", label: "Imagen", type: "image" },
                    { name: "orden", label: "Orden", type: "number" },
                  ]}
                />
              ) : null}

              {activo === "staff" ? (
                <RecordManager
                  tabla="staff"
                  titulo="Miembros del staff"
                  titleField="nombre"
                  tipoImagen="staff"
                  orderBy={{ column: "orden", ascending: true }}
                  defaults={{ nombre: "", cargo: "", descripcion: "", imagen_url: null, orden: 0 }}
                  campos={[
                    { name: "nombre", label: "Nombre", type: "text", required: true, maxLength: 120 },
                    { name: "cargo", label: "Cargo", type: "text", maxLength: 120 },
                    { name: "descripcion", label: "Descripción", type: "textarea", maxLength: 600 },
                    { name: "imagen_url", label: "Foto", type: "image" },
                    { name: "orden", label: "Orden", type: "number" },
                  ]}
                />
              ) : null}

              {activo === "noticias" ? (
                <NoticiasManager
                  userId={userId}
                  puedeCrear={esAdmin || esRedactor}
                  puedeAprobar={esAdmin || esEditor}
                  puedeEditarTodo={esAdmin || esEditor}
                  puedeEliminar={esAdmin}
                />
              ) : null}

              {activo === "contenido" ? <ContenidoEditor /> : null}

              {activo === "footer" ? (
                <RecordManager
                  tabla="enlaces_footer"
                  titulo="Enlaces del footer"
                  titleField="etiqueta"
                  tipoImagen="proyecto"
                  mostrarImagen={false}
                  orderBy={{ column: "orden", ascending: true }}
                  defaults={{ grupo: "Sitio", etiqueta: "", url: "/", orden: 0 }}
                  campos={[
                    { name: "grupo", label: "Columna (Sitio, Contenido, Contacto)", type: "text", required: true, maxLength: 40 },
                    { name: "etiqueta", label: "Texto del enlace", type: "text", required: true, maxLength: 80 },
                    { name: "url", label: "URL o ruta", type: "text", required: true, maxLength: 300 },
                    { name: "orden", label: "Orden", type: "number" },
                  ]}
                />
              ) : null}

              {activo === "usuarios" ? <UsuariosManager /> : null}
            </div>
          </>
        )}
      </div>
    </SiteLayout>
  );
}
