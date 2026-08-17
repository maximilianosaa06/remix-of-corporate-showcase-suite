import { useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { Check, Loader2, Pencil, Plus, Trash2, X } from "lucide-react";
import { supabase } from "@/integrations/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Switch } from "@/components/ui/switch";
import { mediaUrl, slugify, uploadMedio } from "@/lib/media";

type Noticia = {
  id: string;
  slug: string;
  titulo: string;
  redactor: string;
  resumen: string;
  cuerpo: string;
  imagen_url: string | null;
  publicada: boolean;
  estado: string;
  autor_id: string | null;
};

type Form = Omit<Noticia, "id" | "slug">;

const vacio: Form = {
  titulo: "",
  redactor: "",
  resumen: "",
  cuerpo: "",
  imagen_url: null,
  publicada: true,
  estado: "pendiente",
  autor_id: null,
};

export function NoticiasManager({
  puedeCrear,
  puedeAprobar,
  puedeEditarTodo,
  puedeEliminar = false,
  userId,
}: {
  puedeCrear: boolean;
  puedeAprobar: boolean;
  puedeEditarTodo: boolean;
  puedeEliminar?: boolean;
  userId: string;
}) {
  const queryClient = useQueryClient();
  const [editing, setEditing] = useState<Noticia | null>(null);
  const [values, setValues] = useState<Form>(vacio);
  const [subiendo, setSubiendo] = useState(false);

  const listQuery = useQuery({
    queryKey: ["admin", "noticias"],
    queryFn: async () => {
      const { data, error } = await supabase
        .from("noticias")
        .select("id, slug, titulo, redactor, resumen, cuerpo, imagen_url, publicada, estado, autor_id")
        .order("created_at", { ascending: false });
      if (error) throw new Error(error.message);
      return (data ?? []) as Noticia[];
    },
  });

  const reset = () => {
    setEditing(null);
    setValues(vacio);
  };

  const invalidar = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "noticias"] });
    queryClient.invalidateQueries({ queryKey: ["noticias"] });
  };

  const saveMutation = useMutation({
    mutationFn: async () => {
      if (editing) {
        const payload: Record<string, unknown> = {
          titulo: values.titulo,
          redactor: values.redactor,
          resumen: values.resumen,
          cuerpo: values.cuerpo,
          imagen_url: values.imagen_url,
          publicada: values.publicada,
        };
        if (puedeAprobar) payload["estado"] = values.estado;
        const { error } = await (supabase.from("noticias") as any).update(payload).eq("id", editing.id);
        if (error) throw new Error(error.message);
      } else {
        const { error } = await supabase.from("noticias").insert({
          ...values,
          estado: puedeAprobar ? values.estado : "pendiente",
          autor_id: userId,
          slug: `${slugify(values.titulo) || "noticia"}-${crypto.randomUUID().slice(0, 6)}`,
        });
        if (error) throw new Error(error.message);
      }
    },
    onSuccess: () => {
      toast.success(editing ? "Noticia actualizada" : "Noticia creada en estado pendiente");
      reset();
      invalidar();
    },
    onError: (e: Error) => toast.error("No se pudo guardar", { description: e.message }),
  });

  const estadoMutation = useMutation({
    mutationFn: async ({ id, estado }: { id: string; estado: string }) => {
      const { error } = await supabase.from("noticias").update({ estado }).eq("id", id);
      if (error) throw new Error(error.message);
    },
    onSuccess: () => {
      toast.success("Estado actualizado");
      invalidar();
    },
    onError: (e: Error) => toast.error("No se pudo cambiar el estado", { description: e.message }),
  });

  const deleteMutation = useMutation({
    mutationFn: async (id: string) => {
      const { error } = await supabase.from("noticias").delete().eq("id", id);
      if (error) throw new Error(error.message);
    },
    onSuccess: () => {
      toast.success("Noticia eliminada");
      reset();
      invalidar();
    },
    onError: (e: Error) => toast.error("No se pudo eliminar", { description: e.message }),
  });

  const puedeEditar = (n: Noticia) =>
    puedeEditarTodo || (n.autor_id === userId && n.estado === "pendiente");

  return (
    <section className="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
      <div>
        <h2 className="text-lg font-bold">Noticias</h2>
        {listQuery.isLoading ? (
          <p className="mt-4 text-sm text-muted-foreground">Cargando…</p>
        ) : (
          <ul className="mt-4 space-y-3">
            {(listQuery.data ?? []).map((n) => (
              <li
                key={n.id}
                className="flex flex-wrap items-center gap-3 rounded-md bg-card p-3 card-shadow"
              >
                <img
                  src={mediaUrl(n.imagen_url, "noticia")}
                  alt=""
                  className="size-12 shrink-0 rounded object-cover"
                />
                <div className="min-w-0 flex-1 basis-[60%]">
                  <p className="break-words text-sm font-semibold">{n.titulo}</p>
                  <p className="break-words text-xs text-muted-foreground">
                    <span
                      className={
                        n.estado === "aprobada"
                          ? "font-semibold text-primary"
                          : "font-semibold text-destructive"
                      }
                    >
                      {n.estado === "aprobada" ? "Aprobada" : "Pendiente"}
                    </span>{" "}
                    · {n.resumen}
                  </p>
                </div>
                <div className="ml-auto flex shrink-0 items-center gap-2">
                {puedeAprobar ? (
                  <Button
                    size="icon"
                    variant={n.estado === "aprobada" ? "outline" : "destructive"}
                    aria-label={n.estado === "aprobada" ? "Marcar como pendiente" : "Aprobar noticia"}
                    onClick={() =>
                      estadoMutation.mutate({
                        id: n.id,
                        estado: n.estado === "aprobada" ? "pendiente" : "aprobada",
                      })
                    }
                  >
                    {n.estado === "aprobada" ? <X className="size-4" /> : <Check className="size-4" />}
                  </Button>
                ) : null}
                {puedeEditar(n) ? (
                  <Button
                    size="icon"
                    variant="outline"
                    aria-label="Editar"
                    onClick={() => {
                      setEditing(n);
                      const { id: _id, slug: _slug, ...rest } = n;
                      setValues(rest);
                    }}
                  >
                    <Pencil className="size-4" />
                  </Button>
                ) : null}
                {puedeEliminar ? (
                  <Button
                    size="icon"
                    variant="destructive"
                    aria-label="Eliminar"
                    onClick={() => {
                      if (confirm("¿Eliminar esta noticia?")) deleteMutation.mutate(n.id);
                    }}
                  >
                    <Trash2 className="size-4" />
                  </Button>
                ) : null}
                </div>
              </li>
            ))}
            {(listQuery.data ?? []).length === 0 ? (
              <li className="text-sm text-muted-foreground">Aún no hay noticias.</li>
            ) : null}
          </ul>
        )}
      </div>

      {puedeCrear || editing ? (
        <form
          onSubmit={(e) => {
            e.preventDefault();
            saveMutation.mutate();
          }}
          className="h-fit rounded-md bg-panel p-4 card-shadow"
        >
          <div className="flex items-center justify-between">
            <h3 className="text-sm font-bold">{editing ? "Editar noticia" : "Nueva noticia"}</h3>
            {editing ? (
              <Button type="button" size="icon" variant="ghost" aria-label="Cancelar" onClick={reset}>
                <X className="size-4" />
              </Button>
            ) : (
              <Plus className="size-4 text-muted-foreground" />
            )}
          </div>

          <div className="mt-4 space-y-4">
            <div className="space-y-1">
              <Label htmlFor="n-titulo">Título</Label>
              <Input
                id="n-titulo"
                value={values.titulo}
                maxLength={160}
                onChange={(e) => setValues((v) => ({ ...v, titulo: e.target.value }))}
              />
            </div>
            <div className="space-y-1">
              <Label htmlFor="n-redactor">Redactor</Label>
              <Input
                id="n-redactor"
                value={values.redactor}
                maxLength={120}
                onChange={(e) => setValues((v) => ({ ...v, redactor: e.target.value }))}
              />
            </div>
            <div className="space-y-1">
              <Label htmlFor="n-resumen">Resumen</Label>
              <Textarea
                id="n-resumen"
                rows={3}
                value={values.resumen}
                maxLength={400}
                onChange={(e) => setValues((v) => ({ ...v, resumen: e.target.value }))}
              />
            </div>
            <div className="space-y-1">
              <Label htmlFor="n-cuerpo">Cuerpo (un párrafo por línea)</Label>
              <Textarea
                id="n-cuerpo"
                rows={6}
                value={values.cuerpo}
                maxLength={8000}
                onChange={(e) => setValues((v) => ({ ...v, cuerpo: e.target.value }))}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="n-imagen">Imagen</Label>
              <img
                src={mediaUrl(values.imagen_url, "noticia")}
                alt=""
                className="h-28 w-full rounded object-cover"
              />
              <Input
                id="n-imagen"
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
                    setValues((v) => ({ ...v, imagen_url: path }));
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
            <div className="flex items-center gap-3">
              <Switch
                id="n-publicada"
                checked={values.publicada}
                onCheckedChange={(checked) => setValues((v) => ({ ...v, publicada: checked }))}
              />
              <Label htmlFor="n-publicada">Noticia pública</Label>
            </div>
            {puedeAprobar ? (
              <div className="flex items-center gap-3">
                <Switch
                  id="n-estado"
                  checked={values.estado === "aprobada"}
                  onCheckedChange={(checked) =>
                    setValues((v) => ({ ...v, estado: checked ? "aprobada" : "pendiente" }))
                  }
                />
                <Label htmlFor="n-estado">Aprobada</Label>
              </div>
            ) : (
              <p className="text-xs text-muted-foreground">
                Las noticias que creas quedan en estado pendiente hasta que un editor las apruebe.
              </p>
            )}
          </div>

          <Button
            type="submit"
            variant="destructive"
            className="mt-5 w-full"
            disabled={values.titulo.trim().length === 0 || subiendo || saveMutation.isPending}
          >
            {saveMutation.isPending || subiendo ? (
              <Loader2 className="mr-2 size-4 animate-spin" />
            ) : null}
            {editing ? "Guardar cambios" : "Crear noticia"}
          </Button>
        </form>
      ) : null}
    </section>
  );
}
