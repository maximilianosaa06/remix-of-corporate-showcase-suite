import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { supabase } from "@/integrations/supabase/client";
import { Button } from "@/components/ui/button";

const ROLES = ["admin", "editor", "redactor", "guest"] as const;
type Rol = (typeof ROLES)[number];

const etiquetas: Record<Rol, string> = {
  admin: "Administrador",
  editor: "Editor",
  redactor: "Redactor",
  guest: "Invitado",
};

export function UsuariosManager() {
  const queryClient = useQueryClient();

  const usuariosQuery = useQuery({
    queryKey: ["admin", "usuarios"],
    queryFn: async () => {
      const [{ data: perfiles, error: e1 }, { data: roles, error: e2 }] = await Promise.all([
        supabase.from("profiles").select("id, email, nombre").order("email"),
        supabase.from("user_roles").select("id, user_id, role"),
      ]);
      if (e1) throw new Error(e1.message);
      if (e2) throw new Error(e2.message);
      return (perfiles ?? []).map((p) => ({
        ...p,
        roles: (roles ?? []).filter((r) => r.user_id === p.id).map((r) => r.role as Rol),
      }));
    },
  });

  const toggleMutation = useMutation({
    mutationFn: async ({ userId, rol, activo }: { userId: string; rol: Rol; activo: boolean }) => {
      if (activo) {
        const { error } = await supabase
          .from("user_roles")
          .delete()
          .eq("user_id", userId)
          .eq("role", rol);
        if (error) throw new Error(error.message);
      } else {
        const { error } = await supabase.from("user_roles").insert({ user_id: userId, role: rol });
        if (error) throw new Error(error.message);
      }
    },
    onSuccess: () => {
      toast.success("Roles actualizados");
      queryClient.invalidateQueries({ queryKey: ["admin", "usuarios"] });
    },
    onError: (e: Error) => toast.error("No se pudo actualizar el rol", { description: e.message }),
  });

  return (
    <section>
      <h2 className="text-lg font-bold">Usuarios y roles</h2>
      <p className="mt-1 text-sm text-muted-foreground">
        Activa o desactiva los roles de cada cuenta. Un usuario puede tener más de un rol.
      </p>

      {usuariosQuery.isLoading ? (
        <p className="mt-4 text-sm text-muted-foreground">Cargando…</p>
      ) : (
        <ul className="mt-4 space-y-3">
          {(usuariosQuery.data ?? []).map((u) => (
            <li key={u.id} className="rounded-md bg-card p-4 card-shadow">
              <p className="text-sm font-semibold">{u.email}</p>
              <div className="mt-3 flex flex-wrap gap-2">
                {ROLES.map((rol) => {
                  const activo = u.roles.includes(rol);
                  return (
                    <Button
                      key={rol}
                      size="sm"
                      variant={activo ? "destructive" : "outline"}
                      disabled={toggleMutation.isPending}
                      onClick={() => toggleMutation.mutate({ userId: u.id, rol, activo })}
                    >
                      {etiquetas[rol]}
                    </Button>
                  );
                })}
              </div>
            </li>
          ))}
        </ul>
      )}
    </section>
  );
}
