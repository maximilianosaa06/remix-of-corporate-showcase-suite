import { Link } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { Facebook, Instagram, Linkedin, Twitter, Youtube } from "lucide-react";
import logoAsset from "@/assets/logo-sfl.png.asset.json";
import { listEnlacesFooter } from "@/lib/content.functions";

type Enlace = { id: string; grupo: string; etiqueta: string; url: string; orden: number };

const fallback: Enlace[] = [
  { id: "1", grupo: "Sitio", etiqueta: "Inicio", url: "/", orden: 1 },
  { id: "2", grupo: "Sitio", etiqueta: "Proyectos", url: "/proyectos", orden: 2 },
  { id: "3", grupo: "Sitio", etiqueta: "Staff", url: "/staff", orden: 3 },
  { id: "4", grupo: "Contenido", etiqueta: "Noticias", url: "/noticias", orden: 1 },
  { id: "5", grupo: "Contenido", etiqueta: "Contacto", url: "/contacto", orden: 2 },
  { id: "6", grupo: "Contenido", etiqueta: "Iniciar sesión", url: "/login", orden: 3 },
];

export function SiteFooter() {
  const { data } = useQuery({
    queryKey: ["enlaces-footer"],
    queryFn: () => listEnlacesFooter(),
  });

  const enlaces = (data && data.length > 0 ? (data as Enlace[]) : fallback);
  const grupos = Array.from(new Set(enlaces.map((e) => e.grupo)));

  return (
    <footer className="bg-brand text-brand-foreground">
      <div className="mx-auto max-w-6xl px-4 py-10">
        <p className="text-xs opacity-80">Software Factory Lab</p>
        <img src={logoAsset.url} alt="SFL ULS Lab" width={150} height={50} className="mt-2 h-11 w-auto" />

        <div className="mt-8 grid grid-cols-2 gap-6 text-sm sm:grid-cols-3">
          {grupos.map((grupo) => (
            <div key={grupo}>
              <p className="mb-2 font-semibold">{grupo}</p>
              <ul className="space-y-1 opacity-85">
                {enlaces
                  .filter((e) => e.grupo === grupo)
                  .map((e) => (
                    <li key={e.id}>
                      {e.url.startsWith("/") ? (
                        <Link to={e.url}>{e.etiqueta}</Link>
                      ) : (
                        <a href={e.url}>{e.etiqueta}</a>
                      )}
                    </li>
                  ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="mt-8 border-t border-brand-foreground/30 pt-6 text-center">
          <p className="font-semibold">Síguenos en:</p>
          <div className="mt-3 flex justify-center gap-4 opacity-90">
            <Facebook className="size-4" />
            <Linkedin className="size-4" />
            <Twitter className="size-4" />
            <Instagram className="size-4" />
            <Youtube className="size-4" />
          </div>
        </div>

        <p className="mt-6 text-xs opacity-80">© SFL, SPA. Ningún derecho reservado</p>
      </div>
    </footer>
  );
}
