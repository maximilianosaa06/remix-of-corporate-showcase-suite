import { Link } from "@tanstack/react-router";
import { Menu, X } from "lucide-react";
import { useEffect, useState } from "react";
import logoAsset from "@/assets/logo-sfl-color.png.asset.json";
import { supabase } from "@/integrations/supabase/client";

const navLinks = [
  { to: "/", label: "Sobre nosotros" },
  { to: "/proyectos", label: "Proyectos" },
  { to: "/staff", label: "Staff" },
  { to: "/noticias", label: "Noticias" },
  { to: "/contacto", label: "Contáctenos" },
] as const;

export function SiteHeader() {
  const [open, setOpen] = useState(false);
  const [autenticado, setAutenticado] = useState(false);

  useEffect(() => {
    const { data: sub } = supabase.auth.onAuthStateChange((_event, session) => {
      setAutenticado(!!session);
    });
    supabase.auth.getSession().then(({ data }) => setAutenticado(!!data.session));
    return () => sub.subscription.unsubscribe();
  }, []);

  const cuentaLink = autenticado
    ? { to: "/admin" as const, label: "Panel de administración" }
    : { to: "/login" as const, label: "Iniciar sesión" };

  return (
    <header className="sticky top-0 z-50 border-b-2 border-brand bg-background text-foreground">
      <div className="mx-auto grid max-w-6xl grid-cols-[auto_minmax(0,1fr)_auto] items-center gap-3 px-4 py-3 md:flex md:justify-between">
        <button
          type="button"
          aria-label={open ? "Cerrar menú" : "Abrir menú"}
          aria-expanded={open}
          onClick={() => setOpen((v) => !v)}
          className="rounded-md p-2 transition-colors hover:bg-muted md:hidden"
        >
          {open ? <X className="size-6" /> : <Menu className="size-6" />}
        </button>

        <Link
          to="/"
          className="justify-self-center md:justify-self-start"
          aria-label="SFL ULS Lab — inicio"
        >
          <img src={logoAsset.url} alt="SFL ULS Lab" width={120} height={40} className="h-9 w-auto" />
        </Link>

        <div className="flex shrink-0 items-center gap-6 justify-self-end">
          <nav className="hidden md:block">
            <ul className="flex flex-wrap items-center gap-6">
              {navLinks.map((link) => (
                <li key={link.to}>
                  <Link
                    to={link.to}
                    className="text-sm font-medium text-foreground transition-colors hover:text-primary"
                    activeProps={{ className: "text-sm font-semibold text-primary" }}
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>
          <div className="flex shrink-0 items-center gap-3">
            <span className="rounded-md border border-border px-2 py-1 text-xs font-semibold">ES</span>
            <Link to={cuentaLink.to} className="hidden text-sm font-medium hover:underline sm:inline">
              {cuentaLink.label}
            </Link>
          </div>
        </div>
      </div>

      {open ? (
        <nav className="border-t border-border bg-muted md:hidden">
          <ul className="mx-auto max-w-6xl px-4 pb-4">
            {navLinks.map((link) => (
              <li key={link.to} className="border-b border-border">
                <Link
                  to={link.to}
                  onClick={() => setOpen(false)}
                  className="block py-3 text-sm font-medium"
                  activeProps={{ className: "block py-3 text-sm font-bold text-primary" }}
                >
                  {link.label}
                </Link>
              </li>
            ))}
            <li className="sm:hidden">
              <Link
                to={cuentaLink.to}
                onClick={() => setOpen(false)}
                className="block py-3 text-sm font-medium"
              >
                {cuentaLink.label}
              </Link>
            </li>
          </ul>
        </nav>
      ) : null}
    </header>
  );
}
