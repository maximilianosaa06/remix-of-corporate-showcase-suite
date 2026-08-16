import { useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { Loader2, Pencil, Plus, Trash2, X } from "lucide-react";
import { supabase } from "@/integrations/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Switch } from "@/components/ui/switch";
import { mediaUrl, uploadMedio } from "@/lib/media";

export type Campo = {
  name: string;
  label: string;
  type: "text" | "textarea" | "number" | "image" | "switch";
  required?: boolean;
  maxLength?: number;
};

type Registro = Record<string, unknown> & { id: string };

export function RecordManager({
  tabla,
  titulo,
  campos,
  titleField,
  orderBy,
  tipoImagen,
  defaults,
  beforeSave,
  mostrarImagen = true,
}: {
  tabla: "proyectos" | "staff" | "noticias" | "enlaces_footer";
  titulo: string;
  campos: Campo[];
  titleField: string;
  orderBy: { column: string; ascending: boolean };
  tipoImagen: "proyecto" | "staff" | "noticia";
  defaults: Record<string, unknown>;
  beforeSave?: (values: Record<string, unknown>) => Record<string, unknown>;
  mostrarImagen?: boolean;
}) {
  const queryClient = useQueryClient();
  const [editing, setEditing] = useState<Registro | null>(null);
  const [values, setValues] = useState<Record<string, unknown>>(defaults);
  const [subiendo, setSubiendo] = useState(false);

  const listQuery = useQuery({
    queryKey: ["admin", tabla],
    queryFn: async () => {
      const { data, error } = await supabase
        .from(tabla)
        .select("*")
        .order(orderBy.column, { ascending: orderBy.ascending });
      if (error) throw new Error(error.message);
      return (data ?? []) as Registro[];
    },
  });

  const reset = () => {
    setEditing(null);
    setValues(defaults);
  };

  const saveMutation = useMutation({
    mutationFn: async () => {
      const payload = beforeSave ? beforeSave(values) : values;
      if (editing) {
        const { error } = await (supabase.from(tabla) as any).update(payload).eq("id", editing.id);
        if (error) throw new Error(error.message);
      } else {
        const { error } = await (supabase.from(tabla) as any).insert(payload);
        if (error) throw new Error(error.message);
      }
    },
    onSuccess: () => {
      toast.success(editing ? "Registro actualizado" : "Registro creado");
      reset();
      queryClient.invalidateQueries({ queryKey: ["admin", tabla] });
    },
    onError: (e: Error) => toast.error("No se pudo guardar", { description: e.message }),
  });

  const deleteMutation = useMutation({
    mutationFn: async (id: string) => {
      const { error } = await supabase.from(tabla).delete().eq("id", id);
      if (error) throw new Error(error.message);
    },
    onSuccess: () => {
      toast.success("Registro eliminado");
      reset();
      queryClient.invalidateQueries({ queryKey: ["admin", tabla] });
    },
    onError: (e: Error) => toast.error("No se pudo eliminar", { description: e.message }),
  });

  const invalido = campos.some(
    (c) => c.required && String((values[c.name] ?? "") as string).trim().length === 0,
  );

  return (
    <section className="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
      <div>
        <h2 className="text-lg font-bold">{titulo}</h2>
        {listQuery.isLoading ? (
          <p className="mt-4 text-sm text-muted-foreground">Cargando…</p>
        ) : (
          <ul className="mt-4 space-y-3">
            {(listQuery.data ?? []).map((row) => (
              <li
                key={row.id}
                className="flex flex-wrap items-center gap-3 rounded-md bg-card p-3 card-shadow"
              >
                {mostrarImagen ? (
                  <img
                    src={mediaUrl(row["imagen_url"] as string | null, tipoImagen)}
                    alt=""
                    className="size-12 shrink-0 rounded object-cover"
                  />
                ) : null}
                <div className="min-w-0 flex-1 basis-[60%]">
                  <p className="break-words text-sm font-semibold">{String(row[titleField] ?? "")}</p>
                  <p className="break-words text-xs text-muted-foreground">
                    {String(row["cargo"] ?? row["resumen"] ?? row["descripcion"] ?? row["url"] ?? "")}
                  </p>
                </div>
                <div className="ml-auto flex shrink-0 items-center gap-2">
                  <Button
                    size="icon"
                    variant="outline"
                    aria-label="Editar"
                    onClick={() => {
                      setEditing(row);
                      const next: Record<string, unknown> = {};
                      for (const c of campos) next[c.name] = row[c.name] ?? defaults[c.name];
                      setValues(next);
                    }}
                  >
                    <Pencil className="size-4" />
                  </Button>
                  <Button
                    size="icon"
                    variant="destructive"
                    aria-label="Eliminar"
                    onClick={() => {
                      if (confirm("¿Eliminar este registro?")) deleteMutation.mutate(row.id);
                    }}
                  >
                    <Trash2 className="size-4" />
                  </Button>
                </div>
              </li>
            ))}
            {(listQuery.data ?? []).length === 0 ? (
              <li className="text-sm text-muted-foreground">Aún no hay registros.</li>
            ) : null}
          </ul>
        )}
      </div>

      <form
        onSubmit={(e) => {
          e.preventDefault();
          saveMutation.mutate();
        }}
        className="h-fit rounded-md bg-panel p-4 card-shadow"
      >
        <div className="flex items-center justify-between">
          <h3 className="text-sm font-bold">{editing ? "Editar registro" : "Nuevo registro"}</h3>
          {editing ? (
            <Button type="button" size="icon" variant="ghost" aria-label="Cancelar" onClick={reset}>
              <X className="size-4" />
            </Button>
          ) : (
            <Plus className="size-4 text-muted-foreground" />
          )}
        </div>

        <div className="mt-4 space-y-4">
          {campos.map((campo) => (
            <div key={campo.name} className="space-y-1">
              <Label htmlFor={`${tabla}-${campo.name}`}>{campo.label}</Label>
              {campo.type === "textarea" ? (
                <Textarea
                  id={`${tabla}-${campo.name}`}
                  value={String(values[campo.name] ?? "")}
                  maxLength={campo.maxLength ?? 4000}
                  rows={4}
                  onChange={(e) => setValues((v) => ({ ...v, [campo.name]: e.target.value }))}
                />
              ) : campo.type === "number" ? (
                <Input
                  id={`${tabla}-${campo.name}`}
                  type="number"
                  value={Number(values[campo.name] ?? 0)}
                  onChange={(e) =>
                    setValues((v) => ({ ...v, [campo.name]: Number(e.target.value) || 0 }))
                  }
                />
              ) : campo.type === "switch" ? (
                <div className="pt-1">
                  <Switch
                    id={`${tabla}-${campo.name}`}
                    checked={Boolean(values[campo.name])}
                    onCheckedChange={(checked) =>
                      setValues((v) => ({ ...v, [campo.name]: checked }))
                    }
                  />
                </div>
              ) : campo.type === "image" ? (
                <div className="space-y-2">
                  <img
                    src={mediaUrl(values[campo.name] as string | null, tipoImagen)}
                    alt=""
                    className="h-28 w-full rounded object-cover"
                  />
                  <Input
                    id={`${tabla}-${campo.name}`}
                    type="file"
                    accept="image/*"
                    onChange={async (e) => {
                      const file = e.target.files?.[0];
                      if (!file) return;
                      if (file.size > 5 * 1024 * 1024) {
                        toast.error("La imagen debe pesar menos de 5 MB");
                        return;
                      }
                      setSubiendo(true);
                      try {
                        const path = await uploadMedio(file);
                        setValues((v) => ({ ...v, [campo.name]: path }));
                        toast.success("Imagen subida");
                      } catch (err) {
                        toast.error("No se pudo subir la imagen", {
                          description: (err as Error).message,
                        });
                      } finally {
                        setSubiendo(false);
                      }
                    }}
                  />
                </div>
              ) : (
                <Input
                  id={`${tabla}-${campo.name}`}
                  value={String(values[campo.name] ?? "")}
                  maxLength={campo.maxLength ?? 200}
                  onChange={(e) => setValues((v) => ({ ...v, [campo.name]: e.target.value }))}
                />
              )}
            </div>
          ))}
        </div>

        <Button
          type="submit"
          variant="destructive"
          className="mt-5 w-full"
          disabled={invalido || subiendo || saveMutation.isPending}
        >
          {saveMutation.isPending || subiendo ? <Loader2 className="mr-2 size-4 animate-spin" /> : null}
          {editing ? "Guardar cambios" : "Crear"}
        </Button>
      </form>
    </section>
  );
}
