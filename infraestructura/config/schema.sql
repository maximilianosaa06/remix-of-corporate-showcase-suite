--
-- PostgreSQL database dump
--

\restrict WlJPlHxLldSInmUYF6NHFoxGCXQZ6OXIk5ianmde6BpIU5dLNd1I9ohBwL5brTv

-- Dumped from database version 17.10 (Debian 17.10-1.pgdg13+1)
-- Dumped by pg_dump version 17.10 (Debian 17.10-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: app_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.app_user (
    id integer NOT NULL,
    role_id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(150) NOT NULL,
    password character varying(255) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    must_change_password boolean DEFAULT false NOT NULL
);



--
-- Name: app_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.app_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: app_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.app_user_id_seq OWNED BY public.app_user.id;


--
-- Name: audit_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.audit_log (
    id integer NOT NULL,
    user_id integer NOT NULL,
    action character varying(100) NOT NULL,
    entity character varying(100) NOT NULL,
    entity_id integer NOT NULL,
    details text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);



--
-- Name: audit_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.audit_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: audit_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.audit_log_id_seq OWNED BY public.audit_log.id;


--
-- Name: contact_request; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact_request (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    email character varying(150) NOT NULL,
    phone character varying(30),
    subject character varying(255),
    message text NOT NULL,
    sent_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    status character varying(50) NOT NULL
);



--
-- Name: contact_request_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contact_request_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: contact_request_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contact_request_id_seq OWNED BY public.contact_request.id;


--
-- Name: footer_info; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.footer_info (
    id integer NOT NULL,
    email character varying(150),
    phone character varying(30),
    address character varying(255),
    copyright_text character varying(255),
    social_facebook character varying(500),
    social_linkedin character varying(500),
    social_twitter character varying(500),
    social_instagram character varying(500),
    social_youtube character varying(500)
);



--
-- Name: footer_info_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.footer_info_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: footer_info_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.footer_info_id_seq OWNED BY public.footer_info.id;


--
-- Name: news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news (
    id integer NOT NULL,
    author_id integer NOT NULL,
    editor_id integer,
    status_id integer NOT NULL,
    tag_id integer,
    title character varying(255) NOT NULL,
    subtitle character varying(255),
    content text NOT NULL,
    image character varying(255),
    publication_date timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);



--
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_id_seq OWNED BY public.news.id;


--
-- Name: news_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news_status (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);



--
-- Name: news_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: news_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_status_id_seq OWNED BY public.news_status.id;


--
-- Name: project; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.project (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    description text,
    image character varying(255),
    link character varying(255),
    active boolean DEFAULT true NOT NULL
);



--
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.project_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.project_id_seq OWNED BY public.project.id;


--
-- Name: role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    description text
);



--
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_id_seq OWNED BY public.role.id;


--
-- Name: service; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    description text,
    image character varying(255),
    link character varying(255),
    active boolean DEFAULT true NOT NULL
);



--
-- Name: service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_id_seq OWNED BY public.service.id;


--
-- Name: staff_member; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.staff_member (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    "position" character varying(100),
    photo character varying(255),
    description text
);



--
-- Name: staff_member_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.staff_member_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: staff_member_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.staff_member_id_seq OWNED BY public.staff_member.id;


--
-- Name: tag; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tag (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);



--
-- Name: tag_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tag_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: tag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tag_id_seq OWNED BY public.tag.id;


--
-- Name: app_user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user ALTER COLUMN id SET DEFAULT nextval('public.app_user_id_seq'::regclass);


--
-- Name: audit_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.audit_log ALTER COLUMN id SET DEFAULT nextval('public.audit_log_id_seq'::regclass);


--
-- Name: contact_request id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_request ALTER COLUMN id SET DEFAULT nextval('public.contact_request_id_seq'::regclass);


--
-- Name: footer_info id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.footer_info ALTER COLUMN id SET DEFAULT nextval('public.footer_info_id_seq'::regclass);


--
-- Name: news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN id SET DEFAULT nextval('public.news_id_seq'::regclass);


--
-- Name: news_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news_status ALTER COLUMN id SET DEFAULT nextval('public.news_status_id_seq'::regclass);


--
-- Name: project id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project ALTER COLUMN id SET DEFAULT nextval('public.project_id_seq'::regclass);


--
-- Name: role id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role ALTER COLUMN id SET DEFAULT nextval('public.role_id_seq'::regclass);


--
-- Name: service id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service ALTER COLUMN id SET DEFAULT nextval('public.service_id_seq'::regclass);


--
-- Name: staff_member id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff_member ALTER COLUMN id SET DEFAULT nextval('public.staff_member_id_seq'::regclass);


--
-- Name: tag id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tag ALTER COLUMN id SET DEFAULT nextval('public.tag_id_seq'::regclass);


--
-- Data for Name: app_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.app_user (id, role_id, username, email, password, active, must_change_password) FROM stdin;
\.


--
-- Data for Name: audit_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.audit_log (id, user_id, action, entity, entity_id, details, created_at) FROM stdin;
\.


--
-- Data for Name: contact_request; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contact_request (id, name, email, phone, subject, message, sent_at, status) FROM stdin;
\.


--
-- Data for Name: footer_info; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.footer_info (id, email, phone, address) FROM stdin;
\.


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.news (id, author_id, editor_id, status_id, tag_id, title, subtitle, content, image, publication_date, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: news_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.news_status (id, name) FROM stdin;
\.


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.project (id, name, description, image, link, active) FROM stdin;
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role (id, name, description) FROM stdin;
\.


--
-- Data for Name: service; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service (id, name, description, image, link, active) FROM stdin;
\.


--
-- Data for Name: staff_member; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.staff_member (id, name, "position", photo, description) FROM stdin;
\.


--
-- Data for Name: tag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tag (id, name) FROM stdin;
\.


--
-- Name: app_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.app_user_id_seq', 1, false);


--
-- Name: audit_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.audit_log_id_seq', 1, false);


--
-- Name: contact_request_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_request_id_seq', 1, false);


--
-- Name: footer_info_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.footer_info_id_seq', 1, false);


--
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_id_seq', 1, false);


--
-- Name: news_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_status_id_seq', 1, false);


--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.project_id_seq', 1, false);


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_id_seq', 1, false);


--
-- Name: service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_id_seq', 1, false);


--
-- Name: staff_member_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.staff_member_id_seq', 1, false);


--
-- Name: tag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tag_id_seq', 1, false);


--
-- Name: app_user app_user_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT app_user_email_key UNIQUE (email);


--
-- Name: app_user app_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT app_user_pkey PRIMARY KEY (id);


--
-- Name: app_user app_user_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT app_user_username_key UNIQUE (username);


--
-- Name: audit_log audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.audit_log
    ADD CONSTRAINT audit_log_pkey PRIMARY KEY (id);


--
-- Name: contact_request contact_request_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_request
    ADD CONSTRAINT contact_request_pkey PRIMARY KEY (id);


--
-- Name: footer_info footer_info_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.footer_info
    ADD CONSTRAINT footer_info_pkey PRIMARY KEY (id);


--
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- Name: news_status news_status_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news_status
    ADD CONSTRAINT news_status_name_key UNIQUE (name);


--
-- Name: news_status news_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news_status
    ADD CONSTRAINT news_status_pkey PRIMARY KEY (id);


--
-- Name: project project_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: role role_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_name_key UNIQUE (name);


--
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- Name: service service_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service
    ADD CONSTRAINT service_pkey PRIMARY KEY (id);


--
-- Name: staff_member staff_member_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff_member
    ADD CONSTRAINT staff_member_pkey PRIMARY KEY (id);


--
-- Name: tag tag_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tag
    ADD CONSTRAINT tag_name_key UNIQUE (name);


--
-- Name: tag tag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tag
    ADD CONSTRAINT tag_pkey PRIMARY KEY (id);


--
-- Name: audit_log fk_audit_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.audit_log
    ADD CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES public.app_user(id);


--
-- Name: news fk_news_author; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT fk_news_author FOREIGN KEY (author_id) REFERENCES public.app_user(id);


--
-- Name: news fk_news_editor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT fk_news_editor FOREIGN KEY (editor_id) REFERENCES public.app_user(id);


--
-- Name: news fk_news_status; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT fk_news_status FOREIGN KEY (status_id) REFERENCES public.news_status(id);


--
-- Name: news fk_news_tag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT fk_news_tag FOREIGN KEY (tag_id) REFERENCES public.tag(id);


--
-- Name: app_user fk_user_role; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES public.role(id);


--
-- PostgreSQL database dump complete
--

\unrestrict WlJPlHxLldSInmUYF6NHFoxGCXQZ6OXIk5ianmde6BpIU5dLNd1I9ohBwL5brTv


--
-- Name: contenido_sitio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contenido_sitio (
    id integer NOT NULL,
    clave character varying(100) NOT NULL,
    sobre_titulo character varying(255),
    sobre_texto text,
    mision_titulo character varying(255),
    mision_texto text,
    vision_titulo character varying(255),
    vision_texto text,
    objetivos_titulo character varying(255),
    objetivos_texto text,
    politicas_titulo character varying(255),
    politicas_texto text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);

CREATE SEQUENCE public.contenido_sitio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.contenido_sitio_id_seq OWNED BY public.contenido_sitio.id;

ALTER TABLE ONLY public.contenido_sitio ALTER COLUMN id SET DEFAULT nextval('public.contenido_sitio_id_seq'::regclass);

ALTER TABLE ONLY public.contenido_sitio
    ADD CONSTRAINT contenido_sitio_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.contenido_sitio
    ADD CONSTRAINT contenido_sitio_clave_key UNIQUE (clave);

ALTER TABLE ONLY public.contenido_sitio
    ADD CONSTRAINT contenido_sitio_clave_check CHECK (clave::text <> ''::text);

--
-- Name: enlaces_footer; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.enlaces_footer (
    id integer NOT NULL,
    grupo character varying(100) NOT NULL,
    etiqueta character varying(150) NOT NULL,
    url character varying(500) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);

CREATE SEQUENCE public.enlaces_footer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.enlaces_footer_id_seq OWNED BY public.enlaces_footer.id;

ALTER TABLE ONLY public.enlaces_footer ALTER COLUMN id SET DEFAULT nextval('public.enlaces_footer_id_seq'::regclass);

ALTER TABLE ONLY public.enlaces_footer
    ADD CONSTRAINT enlaces_footer_pkey PRIMARY KEY (id);

--
-- Data: contenido_sitio defaults
--

COPY public.contenido_sitio (id, clave, sobre_titulo, sobre_texto, mision_titulo, mision_texto) FROM stdin;
1	home	Sobre nosotros	TECH HUB ULS es el laboratorio de desarrollo de software de la Universidad de La Serena. Nuestro equipo trabaja en proyectos reales que apoyan a la comunidad universitaria y regional.	Misión, visión y objetivos	Nuestra misión es formar profesionales competentes mediante la experiencia práctica en desarrollo de software, fomentando la innovación, el trabajo en equipo y la vinculación con el medio.
\.

--
-- Data: enlaces_footer defaults
--

COPY public.enlaces_footer (id, grupo, etiqueta, url, orden) FROM stdin;
1	Sitio	Inicio	/	1
2	Sitio	Proyectos	/proyectos	2
3	Sitio	Staff	/staff	3
4	Contenido	Noticias	/noticias	1
5	Contenido	Contacto	/contacto	2
6	Contenido	Iniciar sesión	/login	3
\.

SELECT pg_catalog.setval('public.contenido_sitio_id_seq', 1, true);
SELECT pg_catalog.setval('public.enlaces_footer_id_seq', 6, true);


