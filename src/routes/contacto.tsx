import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { z } from "zod";
import { toast } from "sonner";
import { SiteLayout } from "@/components/site/SiteLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

export const Route = createFileRoute("/contacto")({
  head: () => ({
    meta: [
      { title: "Contacto — SFL ULS Lab" },
      {
        name: "description",
        content:
          "Escríbenos para postular a un proyecto, solicitar un servicio o resolver dudas sobre SFL ULS Lab.",
      },
      { property: "og:title", content: "Contacto — SFL ULS Lab" },
      {
        property: "og:description",
        content: "Formulario de contacto del Software Factory Lab de la Universidad de La Serena.",
      },
    ],
  }),
  component: ContactoPage,
});

const contactoSchema = z.object({
  nombre: z.string().trim().min(1, "Ingresa tu nombre").max(100, "Máximo 100 caracteres"),
  motivo: z.string().trim().min(1, "Selecciona un motivo"),
  correo: z.string().trim().email("Correo inválido").max(255),
  telefono: z.string().trim().max(20, "Máximo 20 caracteres"),
  cuerpo: z.string().trim().min(1, "Describe el motivo").max(1000, "Máximo 1000 caracteres"),
});

const motivos = ["Consulta general", "Solicitud de servicio", "Postulación", "Prensa"];

function ContactoPage() {
  const [errors, setErrors] = useState<Record<string, string>>({});

  return (
    <SiteLayout>
      <div className="hero-gradient flex min-h-[70vh] items-start justify-center px-4 py-12">
        <form
          noValidate
          onSubmit={(e) => {
            e.preventDefault();
            const data = new FormData(e.currentTarget);
            const parsed = contactoSchema.safeParse({
              nombre: String(data.get("nombre") ?? ""),
              motivo: String(data.get("motivo") ?? ""),
              correo: String(data.get("correo") ?? ""),
              telefono: String(data.get("telefono") ?? ""),
              cuerpo: String(data.get("cuerpo") ?? ""),
            });
            if (!parsed.success) {
              const next: Record<string, string> = {};
              for (const issue of parsed.error.issues) next[String(issue.path[0])] = issue.message;
              setErrors(next);
              return;
            }
            setErrors({});
            e.currentTarget.reset();
            toast.success("Formulario enviado", {
              description: "Gracias por escribirnos, te responderemos pronto.",
            });
          }}
          className="w-full max-w-md rounded-md bg-panel p-6 card-shadow"
        >
          <h1 className="text-lg font-semibold">Envío de formulario de contacto</h1>

          <div className="mt-5 space-y-1">
            <Label htmlFor="nombre">Nombre</Label>
            <Input id="nombre" name="nombre" placeholder="Nombre de contacto" maxLength={100} />
            {errors["nombre"] ? <p className="text-xs text-destructive">{errors["nombre"]}</p> : null}
          </div>

          <div className="mt-4 space-y-1">
            <Label htmlFor="motivo">Motivo de envío</Label>
            <select
              id="motivo"
              name="motivo"
              defaultValue=""
              className="h-9 w-full rounded-md border border-input bg-background px-3 text-sm"
            >
              <option value="" disabled>
                Seleccione motivo de envío
              </option>
              {motivos.map((m) => (
                <option key={m} value={m}>
                  {m}
                </option>
              ))}
            </select>
            {errors["motivo"] ? <p className="text-xs text-destructive">{errors["motivo"]}</p> : null}
          </div>

          <div className="mt-4 space-y-1">
            <Label htmlFor="correo">Correo de contacto</Label>
            <Input id="correo" name="correo" type="email" placeholder="Correo de contacto" maxLength={255} />
            {errors["correo"] ? <p className="text-xs text-destructive">{errors["correo"]}</p> : null}
          </div>

          <div className="mt-4 space-y-1">
            <Label htmlFor="telefono">Teléfono de contacto</Label>
            <Input id="telefono" name="telefono" placeholder="Teléfono de contacto" maxLength={20} />
            {errors["telefono"] ? <p className="text-xs text-destructive">{errors["telefono"]}</p> : null}
          </div>

          <div className="mt-4 space-y-1">
            <Label htmlFor="cuerpo">Cuerpo del motivo</Label>
            <Textarea
              id="cuerpo"
              name="cuerpo"
              rows={6}
              maxLength={1000}
              placeholder="Describa aquí el motivo del contacto"
            />
            {errors["cuerpo"] ? <p className="text-xs text-destructive">{errors["cuerpo"]}</p> : null}
          </div>

          <Button type="submit" variant="destructive" className="mt-6 w-full">
            Enviar formulario
          </Button>
        </form>
      </div>
    </SiteLayout>
  );
}
