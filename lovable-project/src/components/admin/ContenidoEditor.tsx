import { useEffect, useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { Loader2 } from "lucide-react";
import { supabase } from "@/integrations/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

type Contenido = {
  id: string;
  sobre_titulo: string;
  sobre_texto: string;
  mision_titulo: string;
  mision_texto: string;
};

const vacio: Contenido = {
  id: "",
  sobre_titulo: "",
  sobre_texto: "",
  mision_titulo: "",
  mision_texto: "",
};

export function ContenidoEditor() {
  const queryClient = useQueryClient();
  const [values, setValues] = useState<Contenido>(vacio);

  const contenidoQuery = useQuery({
    queryKey: ["admin", "contenido_sitio"],
    queryFn: async () => {
      const { data, error } = await supabase
        .from("contenido_sitio")
        .select("id, sobre_titulo, sobre_texto, mision_titulo, mision_texto")
        .eq("clave", "home")
        .maybeSingle();
      if (error) throw new Error(error.message);
      return (data ?? vacio) as Contenido;
    },
  });

  useEffect(() => {
    if (contenidoQuery.data) setValues(contenidoQuery.data);
  }, [contenidoQuery.data]);

  const saveMutation = useMutation({
    mutationFn: async () => {
      const { id, ...rest } = values;
      const { error } = await supabase.from("contenido_sitio").update(rest).eq("id", id);
      if (error) throw new Error(error.message);
    },
    onSuccess: () => {
      toast.success("Contenido actualizado");
      queryClient.invalidateQueries({ queryKey: ["admin", "contenido_sitio"] });
      queryClient.invalidateQueries({ queryKey: ["contenido-sitio"] });
    },
    onError: (e: Error) => toast.error("No se pudo guardar", { description: e.message }),
  });

  if (contenidoQuery.isLoading) {
    return <p className="text-sm text-muted-foreground">Cargando…</p>;
  }

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();
        saveMutation.mutate();
      }}
      className="max-w-2xl space-y-4 rounded-md bg-panel p-4 card-shadow"
    >
      <h2 className="text-lg font-bold">Sobre nosotros, misión y visión</h2>

      <div className="space-y-1">
        <Label htmlFor="sobre_titulo">Título «Sobre nosotros»</Label>
        <Input
          id="sobre_titulo"
          value={values.sobre_titulo}
          maxLength={120}
          onChange={(e) => setValues((v) => ({ ...v, sobre_titulo: e.target.value }))}
        />
      </div>
      <div className="space-y-1">
        <Label htmlFor="sobre_texto">Texto «Sobre nosotros»</Label>
        <Textarea
          id="sobre_texto"
          rows={5}
          value={values.sobre_texto}
          maxLength={1500}
          onChange={(e) => setValues((v) => ({ ...v, sobre_texto: e.target.value }))}
        />
      </div>
      <div className="space-y-1">
        <Label htmlFor="mision_titulo">Título «Misión y visión»</Label>
        <Input
          id="mision_titulo"
          value={values.mision_titulo}
          maxLength={120}
          onChange={(e) => setValues((v) => ({ ...v, mision_titulo: e.target.value }))}
        />
      </div>
      <div className="space-y-1">
        <Label htmlFor="mision_texto">Texto «Misión, visión y objetivos»</Label>
        <Textarea
          id="mision_texto"
          rows={5}
          value={values.mision_texto}
          maxLength={1500}
          onChange={(e) => setValues((v) => ({ ...v, mision_texto: e.target.value }))}
        />
      </div>

      <Button type="submit" variant="destructive" disabled={saveMutation.isPending}>
        {saveMutation.isPending ? <Loader2 className="mr-2 size-4 animate-spin" /> : null}
        Guardar cambios
      </Button>
    </form>
  );
}
