import proyectoFallback from "@/assets/proyecto-1.jpg";
import staffFallback from "@/assets/staff-1.jpg";
import noticiaFallback from "@/assets/noticia-1.jpg";

export function mediaUrl(path: string | null | undefined, tipo: "proyecto" | "staff" | "noticia") {
  if (!path) {
    return tipo === "proyecto" ? proyectoFallback : tipo === "staff" ? staffFallback : noticiaFallback;
  }
  if (path.startsWith("http://") || path.startsWith("https://") || path.startsWith("/")) return path;
  return `/api/public/medios/${path}`;
}

export async function uploadMedio(file: File) {
  const { supabase } = await import("@/integrations/supabase/client");
  const ext = file.name.split(".").pop()?.toLowerCase().replace(/[^a-z0-9]/g, "") || "bin";
  const path = `${crypto.randomUUID()}.${ext}`;
  const { error } = await supabase.storage.from("medios").upload(path, file, {
    contentType: file.type || "application/octet-stream",
    upsert: false,
  });
  if (error) throw new Error(error.message);
  return path;
}

export function slugify(value: string) {
  return value
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .slice(0, 90);
}
