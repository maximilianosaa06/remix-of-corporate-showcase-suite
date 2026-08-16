import proyecto1 from "@/assets/proyecto-1.jpg";
import proyecto2 from "@/assets/proyecto-2.jpg";
import noticia1 from "@/assets/noticia-1.jpg";
import staff1 from "@/assets/staff-1.jpg";

export type Proyecto = {
  id: string;
  titulo: string;
  descripcion: string;
  imagen: string;
};

export type Miembro = {
  id: string;
  nombre: string;
  cargo: string;
  descripcion: string;
  imagen: string;
};

export type Noticia = {
  id: string;
  titulo: string;
  redactor: string;
  resumen: string;
  cuerpo: string[];
  imagen: string;
};

export const proyectos: Proyecto[] = [
  {
    id: "plataforma-academica",
    titulo: "Plataforma Académica",
    descripcion:
      "Sistema web para la gestión de asignaturas, matrículas y seguimiento académico de estudiantes.",
    imagen: proyecto1,
  },
  {
    id: "gestion-laboratorios",
    titulo: "Gestión de Laboratorios",
    descripcion:
      "Reserva y control de uso de laboratorios, con reportes de disponibilidad en tiempo real.",
    imagen: proyecto2,
  },
  {
    id: "portal-vinculacion",
    titulo: "Portal de Vinculación",
    descripcion:
      "Espacio digital para conectar proyectos de la universidad con empresas y organizaciones de la región.",
    imagen: proyecto1,
  },
  {
    id: "app-terreno",
    titulo: "Aplicación de Terreno",
    descripcion:
      "Aplicación móvil para levantamiento de datos en terreno con sincronización sin conexión.",
    imagen: proyecto2,
  },
  {
    id: "observatorio-datos",
    titulo: "Observatorio de Datos",
    descripcion:
      "Tableros de visualización para el análisis de indicadores institucionales y regionales.",
    imagen: proyecto1,
  },
  {
    id: "sistema-tickets",
    titulo: "Sistema de Soporte",
    descripcion:
      "Mesa de ayuda interna con flujo de tickets, prioridades y métricas de atención.",
    imagen: proyecto2,
  },
];

export const staff: Miembro[] = [
  {
    id: "fernando-flores",
    nombre: "Fernando Flores Cortijo",
    cargo: "Project Manager Officer",
    descripcion:
      "Coordina la planificación de los proyectos del laboratorio y el vínculo con las contrapartes.",
    imagen: staff1,
  },
  {
    id: "luis-hernandez",
    nombre: "Luis Hernández Comunez",
    cargo: "Analista de Riesgos",
    descripcion:
      "Responsable del análisis de riesgos, calidad y aseguramiento de los entregables de cada proyecto.",
    imagen: staff1,
  },
  {
    id: "bernardo-llanos",
    nombre: "Bernardo Llanos",
    cargo: "Arquitecto de Software",
    descripcion:
      "Define la arquitectura técnica de las soluciones y acompaña al equipo de desarrollo.",
    imagen: staff1,
  },
  {
    id: "darren-jason",
    nombre: "Darren Jason",
    cargo: "Desarrollador Full Stack",
    descripcion:
      "Implementa funcionalidades de frontend y backend para las plataformas del laboratorio.",
    imagen: staff1,
  },
  {
    id: "camila-rojas",
    nombre: "Camila Rojas",
    cargo: "Diseñadora UX/UI",
    descripcion:
      "Diseña la experiencia de uso y los sistemas visuales de los productos digitales.",
    imagen: staff1,
  },
  {
    id: "marta-nunez",
    nombre: "Marta Núñez",
    cargo: "Ingeniera de Datos",
    descripcion:
      "Construye los procesos de datos que alimentan los tableros e informes institucionales.",
    imagen: staff1,
  },
];

const cuerpoBase = [
  "Software Factory Lab desarrolla soluciones digitales junto a estudiantes y académicos de la Universidad de La Serena, combinando formación práctica con proyectos reales de la región.",
  "El equipo trabaja con metodologías ágiles, ciclos de entrega cortos y validación permanente con las contrapartes, lo que permite ajustar los productos a las necesidades reales de cada organización.",
  "Durante esta etapa se incorporaron nuevas prácticas de aseguramiento de calidad, revisión de código y documentación técnica, con el objetivo de que cada entrega sea sostenible en el tiempo.",
];

export const noticias: Noticia[] = [
  {
    id: "nueva-ia-escaneo",
    titulo: "Estudiantes crean nueva IA de escaneo de animales",
    redactor: "Periodista 1",
    resumen:
      "Un equipo del laboratorio presentó un modelo de visión computacional para el reconocimiento de fauna local.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
  {
    id: "convenio-regional",
    titulo: "Nuevo convenio de vinculación regional",
    redactor: "Periodista 2",
    resumen:
      "La universidad firmó un acuerdo para desarrollar plataformas digitales junto a municipios de la región.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
  {
    id: "practicas-profesionales",
    titulo: "Se abren postulaciones a prácticas profesionales",
    redactor: "Periodista 1",
    resumen:
      "El laboratorio ofrece cupos de práctica en desarrollo de software, datos y diseño de experiencia.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
  {
    id: "jornada-abierta",
    titulo: "Jornada abierta de proyectos del laboratorio",
    redactor: "Periodista 3",
    resumen:
      "Los equipos presentaron los avances de sus proyectos ante la comunidad universitaria.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
  {
    id: "taller-calidad",
    titulo: "Taller de calidad de software para estudiantes",
    redactor: "Periodista 2",
    resumen:
      "Se realizó un taller práctico sobre pruebas automatizadas e integración continua.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
  {
    id: "nuevo-laboratorio",
    titulo: "Se habilita un nuevo espacio de trabajo colaborativo",
    redactor: "Periodista 3",
    resumen:
      "El nuevo laboratorio permite duplicar la capacidad de estudiantes en proyectos simultáneos.",
    cuerpo: cuerpoBase,
    imagen: noticia1,
  },
];
