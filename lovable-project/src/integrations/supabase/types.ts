export type Json =
  | string
  | number
  | boolean
  | null
  | { [key: string]: Json | undefined }
  | Json[]

export type Database = {
  // Allows to automatically instantiate createClient with right options
  // instead of createClient<Database, { PostgrestVersion: 'XX' }>(URL, KEY)
  __InternalSupabase: {
    PostgrestVersion: "14.15"
  }
  public: {
    Tables: {
      contenido_sitio: {
        Row: {
          clave: string
          created_at: string
          id: string
          mision_texto: string
          mision_titulo: string
          sobre_texto: string
          sobre_titulo: string
          updated_at: string
        }
        Insert: {
          clave: string
          created_at?: string
          id?: string
          mision_texto?: string
          mision_titulo?: string
          sobre_texto?: string
          sobre_titulo?: string
          updated_at?: string
        }
        Update: {
          clave?: string
          created_at?: string
          id?: string
          mision_texto?: string
          mision_titulo?: string
          sobre_texto?: string
          sobre_titulo?: string
          updated_at?: string
        }
        Relationships: []
      }
      enlaces_footer: {
        Row: {
          created_at: string
          etiqueta: string
          grupo: string
          id: string
          orden: number
          updated_at: string
          url: string
        }
        Insert: {
          created_at?: string
          etiqueta: string
          grupo?: string
          id?: string
          orden?: number
          updated_at?: string
          url?: string
        }
        Update: {
          created_at?: string
          etiqueta?: string
          grupo?: string
          id?: string
          orden?: number
          updated_at?: string
          url?: string
        }
        Relationships: []
      }
      noticias: {
        Row: {
          autor_id: string | null
          created_at: string
          cuerpo: string
          estado: string
          id: string
          imagen_url: string | null
          publicada: boolean
          redactor: string
          resumen: string
          slug: string
          titulo: string
          updated_at: string
        }
        Insert: {
          autor_id?: string | null
          created_at?: string
          cuerpo?: string
          estado?: string
          id?: string
          imagen_url?: string | null
          publicada?: boolean
          redactor?: string
          resumen?: string
          slug: string
          titulo: string
          updated_at?: string
        }
        Update: {
          autor_id?: string | null
          created_at?: string
          cuerpo?: string
          estado?: string
          id?: string
          imagen_url?: string | null
          publicada?: boolean
          redactor?: string
          resumen?: string
          slug?: string
          titulo?: string
          updated_at?: string
        }
        Relationships: []
      }
      profiles: {
        Row: {
          created_at: string
          email: string
          id: string
          nombre: string
          updated_at: string
        }
        Insert: {
          created_at?: string
          email?: string
          id: string
          nombre?: string
          updated_at?: string
        }
        Update: {
          created_at?: string
          email?: string
          id?: string
          nombre?: string
          updated_at?: string
        }
        Relationships: []
      }
      proyectos: {
        Row: {
          created_at: string
          descripcion: string
          id: string
          imagen_url: string | null
          orden: number
          titulo: string
          updated_at: string
        }
        Insert: {
          created_at?: string
          descripcion?: string
          id?: string
          imagen_url?: string | null
          orden?: number
          titulo: string
          updated_at?: string
        }
        Update: {
          created_at?: string
          descripcion?: string
          id?: string
          imagen_url?: string | null
          orden?: number
          titulo?: string
          updated_at?: string
        }
        Relationships: []
      }
      staff: {
        Row: {
          cargo: string
          created_at: string
          descripcion: string
          id: string
          imagen_url: string | null
          nombre: string
          orden: number
          updated_at: string
        }
        Insert: {
          cargo?: string
          created_at?: string
          descripcion?: string
          id?: string
          imagen_url?: string | null
          nombre: string
          orden?: number
          updated_at?: string
        }
        Update: {
          cargo?: string
          created_at?: string
          descripcion?: string
          id?: string
          imagen_url?: string | null
          nombre?: string
          orden?: number
          updated_at?: string
        }
        Relationships: []
      }
      user_roles: {
        Row: {
          created_at: string
          id: string
          role: Database["public"]["Enums"]["app_role"]
          user_id: string
        }
        Insert: {
          created_at?: string
          id?: string
          role: Database["public"]["Enums"]["app_role"]
          user_id: string
        }
        Update: {
          created_at?: string
          id?: string
          role?: Database["public"]["Enums"]["app_role"]
          user_id?: string
        }
        Relationships: []
      }
    }
    Views: {
      [_ in never]: never
    }
    Functions: {
      has_role: {
        Args: {
          _role: Database["public"]["Enums"]["app_role"]
          _user_id: string
        }
        Returns: boolean
      }
    }
    Enums: {
      app_role: "admin" | "user" | "editor" | "redactor" | "guest"
    }
    CompositeTypes: {
      [_ in never]: never
    }
  }
}

type DatabaseWithoutInternals = Omit<Database, "__InternalSupabase">

type DefaultSchema = DatabaseWithoutInternals[Extract<keyof Database, "public">]

export type Tables<
  DefaultSchemaTableNameOrOptions extends
    | keyof (DefaultSchema["Tables"] & DefaultSchema["Views"])
    | { schema: keyof DatabaseWithoutInternals },
  TableName extends DefaultSchemaTableNameOrOptions extends {
    schema: keyof DatabaseWithoutInternals
  }
    ? keyof (DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"] &
        DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Views"])
    : never = never,
> = DefaultSchemaTableNameOrOptions extends {
  schema: keyof DatabaseWithoutInternals
}
  ? (DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"] &
      DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Views"])[TableName] extends {
      Row: infer R
    }
    ? R
    : never
  : DefaultSchemaTableNameOrOptions extends keyof (DefaultSchema["Tables"] &
        DefaultSchema["Views"])
    ? (DefaultSchema["Tables"] &
        DefaultSchema["Views"])[DefaultSchemaTableNameOrOptions] extends {
        Row: infer R
      }
      ? R
      : never
    : never

export type TablesInsert<
  DefaultSchemaTableNameOrOptions extends
    | keyof DefaultSchema["Tables"]
    | { schema: keyof DatabaseWithoutInternals },
  TableName extends DefaultSchemaTableNameOrOptions extends {
    schema: keyof DatabaseWithoutInternals
  }
    ? keyof DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"]
    : never = never,
> = DefaultSchemaTableNameOrOptions extends {
  schema: keyof DatabaseWithoutInternals
}
  ? DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"][TableName] extends {
      Insert: infer I
    }
    ? I
    : never
  : DefaultSchemaTableNameOrOptions extends keyof DefaultSchema["Tables"]
    ? DefaultSchema["Tables"][DefaultSchemaTableNameOrOptions] extends {
        Insert: infer I
      }
      ? I
      : never
    : never

export type TablesUpdate<
  DefaultSchemaTableNameOrOptions extends
    | keyof DefaultSchema["Tables"]
    | { schema: keyof DatabaseWithoutInternals },
  TableName extends DefaultSchemaTableNameOrOptions extends {
    schema: keyof DatabaseWithoutInternals
  }
    ? keyof DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"]
    : never = never,
> = DefaultSchemaTableNameOrOptions extends {
  schema: keyof DatabaseWithoutInternals
}
  ? DatabaseWithoutInternals[DefaultSchemaTableNameOrOptions["schema"]]["Tables"][TableName] extends {
      Update: infer U
    }
    ? U
    : never
  : DefaultSchemaTableNameOrOptions extends keyof DefaultSchema["Tables"]
    ? DefaultSchema["Tables"][DefaultSchemaTableNameOrOptions] extends {
        Update: infer U
      }
      ? U
      : never
    : never

export type Enums<
  DefaultSchemaEnumNameOrOptions extends
    | keyof DefaultSchema["Enums"]
    | { schema: keyof DatabaseWithoutInternals },
  EnumName extends DefaultSchemaEnumNameOrOptions extends {
    schema: keyof DatabaseWithoutInternals
  }
    ? keyof DatabaseWithoutInternals[DefaultSchemaEnumNameOrOptions["schema"]]["Enums"]
    : never = never,
> = DefaultSchemaEnumNameOrOptions extends {
  schema: keyof DatabaseWithoutInternals
}
  ? DatabaseWithoutInternals[DefaultSchemaEnumNameOrOptions["schema"]]["Enums"][EnumName]
  : DefaultSchemaEnumNameOrOptions extends keyof DefaultSchema["Enums"]
    ? DefaultSchema["Enums"][DefaultSchemaEnumNameOrOptions]
    : never

export type CompositeTypes<
  PublicCompositeTypeNameOrOptions extends
    | keyof DefaultSchema["CompositeTypes"]
    | { schema: keyof DatabaseWithoutInternals },
  CompositeTypeName extends PublicCompositeTypeNameOrOptions extends {
    schema: keyof DatabaseWithoutInternals
  }
    ? keyof DatabaseWithoutInternals[PublicCompositeTypeNameOrOptions["schema"]]["CompositeTypes"]
    : never = never,
> = PublicCompositeTypeNameOrOptions extends {
  schema: keyof DatabaseWithoutInternals
}
  ? DatabaseWithoutInternals[PublicCompositeTypeNameOrOptions["schema"]]["CompositeTypes"][CompositeTypeName]
  : PublicCompositeTypeNameOrOptions extends keyof DefaultSchema["CompositeTypes"]
    ? DefaultSchema["CompositeTypes"][PublicCompositeTypeNameOrOptions]
    : never

export const Constants = {
  public: {
    Enums: {
      app_role: ["admin", "user", "editor", "redactor", "guest"],
    },
  },
} as const
