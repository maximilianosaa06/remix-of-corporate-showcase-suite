import { createServerFn } from "@tanstack/react-start";
import { z } from "zod";

export const listProyectos = createServerFn({ method: "GET" }).handler(async () => {
  const { createPublicClient } = await import("./content.server");
  const { data, error } = await createPublicClient()
    .from("proyectos")
    .select("id, titulo, descripcion, imagen_url, orden")
    .order("orden", { ascending: true })
    .order("created_at", { ascending: true });
  if (error) throw new Error(error.message);
  return data ?? [];
});

export const listStaff = createServerFn({ method: "GET" }).handler(async () => {
  const { createPublicClient } = await import("./content.server");
  const { data, error } = await createPublicClient()
    .from("staff")
    .select("id, nombre, cargo, descripcion, imagen_url, orden")
    .order("orden", { ascending: true })
    .order("created_at", { ascending: true });
  if (error) throw new Error(error.message);
  return data ?? [];
});

export const listNoticias = createServerFn({ method: "GET" }).handler(async () => {
  const { createPublicClient } = await import("./content.server");
  const { data, error } = await createPublicClient()
    .from("noticias")
    .select("id, slug, titulo, redactor, resumen, imagen_url, created_at")
    .eq("publicada", true)
    .order("created_at", { ascending: false });
  if (error) throw new Error(error.message);
  return data ?? [];
});

export const getNoticia = createServerFn({ method: "GET" })
  .inputValidator((input) => z.object({ slug: z.string().min(1).max(120) }).parse(input))
  .handler(async ({ data }) => {
    const { createPublicClient } = await import("./content.server");
    const { data: row, error } = await createPublicClient()
      .from("noticias")
      .select("id, slug, titulo, redactor, resumen, cuerpo, imagen_url, created_at")
      .eq("slug", data.slug)
      .eq("publicada", true)
      .maybeSingle();
    if (error) throw new Error(error.message);
    return row;
  });

export const getContenidoSitio = createServerFn({ method: "GET" }).handler(async () => {
  const { createPublicClient } = await import("./content.server");
  const { data, error } = await createPublicClient()
    .from("contenido_sitio")
    .select("id, clave, sobre_titulo, sobre_texto, mision_titulo, mision_texto")
    .eq("clave", "home")
    .maybeSingle();
  if (error) throw new Error(error.message);
  return data;
});

export const listEnlacesFooter = createServerFn({ method: "GET" }).handler(async () => {
  const { createPublicClient } = await import("./content.server");
  const { data, error } = await createPublicClient()
    .from("enlaces_footer")
    .select("id, grupo, etiqueta, url, orden")
    .order("grupo", { ascending: true })
    .order("orden", { ascending: true });
  if (error) throw new Error(error.message);
  return data ?? [];
});
