import { createFileRoute, useNavigate } from "@tanstack/react-router";
import { useEffect, useState } from "react";
import { z } from "zod";
import { toast } from "sonner";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { supabase } from "@/integrations/supabase/client";

export const Route = createFileRoute("/login")({
  head: () => ({
    meta: [
      { title: "Iniciar sesión — SFL ULS Lab" },
      {
        name: "description",
        content: "Acceso al panel interno del Software Factory Lab de la Universidad de La Serena.",
      },
      { property: "og:title", content: "Iniciar sesión — SFL ULS Lab" },
      { property: "og:description", content: "Acceso privado para el equipo de SFL ULS Lab." },
      { name: "robots", content: "noindex" },
    ],
  }),
  component: LoginPage,
});

const loginSchema = z.object({
  email: z.string().trim().email("Ingresa un correo válido").max(255),
  password: z.string().min(6, "La contraseña debe tener al menos 6 caracteres").max(120),
});

function LoginPage() {
  const navigate = useNavigate();
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [modo, setModo] = useState<"login" | "signup">("login");
  const [cargando, setCargando] = useState(false);

  useEffect(() => {
    supabase.auth.getSession().then(({ data }) => {
      if (data.session) navigate({ to: "/admin", replace: true });
    });
  }, [navigate]);

  return (
    <SiteLayout>
      <div className="hero-gradient flex min-h-[70vh] items-center justify-center px-4 py-14">
        <form
          noValidate
          onSubmit={async (e) => {
            e.preventDefault();
            const data = new FormData(e.currentTarget);
            const parsed = loginSchema.safeParse({
              email: String(data.get("email") ?? ""),
              password: String(data.get("password") ?? ""),
            });
            if (!parsed.success) {
              const next: Record<string, string> = {};
              for (const issue of parsed.error.issues) next[String(issue.path[0])] = issue.message;
              setErrors(next);
              return;
            }
            setErrors({});
            setCargando(true);
            try {
              if (modo === "signup") {
                const { error } = await supabase.auth.signUp({
                  email: parsed.data.email,
                  password: parsed.data.password,
                  options: { emailRedirectTo: window.location.origin },
                });
                if (error) throw error;
                toast.success("Cuenta creada", {
                  description: "Revisa tu correo para confirmar la cuenta antes de ingresar.",
                });
                setModo("login");
              } else {
                const { error } = await supabase.auth.signInWithPassword({
                  email: parsed.data.email,
                  password: parsed.data.password,
                });
                if (error) throw error;
                navigate({ to: "/admin", replace: true });
              }
            } catch (err) {
              toast.error("No se pudo continuar", { description: (err as Error).message });
            } finally {
              setCargando(false);
            }
          }}
          className="w-full max-w-sm rounded-md bg-panel p-6 card-shadow"
        >
          <h1 className="text-xl font-semibold text-foreground">
            {modo === "login" ? "Login" : "Crear cuenta"}
          </h1>

          <div className="mt-5 space-y-1">
            <Label htmlFor="email">Correo</Label>
            <Input
              id="email"
              name="email"
              type="email"
              placeholder="correo@userena.cl"
              maxLength={255}
              autoComplete="email"
            />
            {errors["email"] ? <p className="text-xs text-destructive">{errors["email"]}</p> : null}
          </div>

          <div className="mt-4 space-y-1">
            <Label htmlFor="password">Contraseña</Label>
            <Input
              id="password"
              name="password"
              type="password"
              placeholder="Contraseña"
              maxLength={120}
              autoComplete={modo === "login" ? "current-password" : "new-password"}
            />
            {errors["password"] ? (
              <p className="text-xs text-destructive">{errors["password"]}</p>
            ) : null}
          </div>

          <Button type="submit" variant="destructive" className="mt-6 w-full" disabled={cargando}>
            {modo === "login" ? "Iniciar sesión" : "Registrarme"}
          </Button>

          <button
            type="button"
            onClick={() => setModo((m) => (m === "login" ? "signup" : "login"))}
            className="mt-3 w-full text-center text-xs text-muted-foreground underline"
          >
            {modo === "login" ? "Crear una cuenta del equipo" : "Ya tengo cuenta"}
          </button>
        </form>
      </div>
    </SiteLayout>
  );
}
