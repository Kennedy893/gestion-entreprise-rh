--
-- PostgreSQL database dump
--

-- Dumped from database version 16.0
-- Dumped by pg_dump version 16.0

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
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
-- Name: annonce; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.annonce (
    id_annonce integer NOT NULL,
    id_besoin integer NOT NULL
);


ALTER TABLE public.annonce OWNER TO postgres;

--
-- Name: annonce_id_annonce_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.annonce_id_annonce_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.annonce_id_annonce_seq OWNER TO postgres;

--
-- Name: annonce_id_annonce_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.annonce_id_annonce_seq OWNED BY public.annonce.id_annonce;


--
-- Name: barem; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.barem (
    id_barem integer NOT NULL,
    libelle character varying(50),
    valeur numeric(5,2)
);


ALTER TABLE public.barem OWNER TO postgres;

--
-- Name: barem_id_barem_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.barem_id_barem_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.barem_id_barem_seq OWNER TO postgres;

--
-- Name: barem_id_barem_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.barem_id_barem_seq OWNED BY public.barem.id_barem;


--
-- Name: besoin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.besoin (
    id_besoin integer NOT NULL,
    experience integer,
    mission character varying(200),
    date_limite timestamp without time zone,
    id_poste integer,
    id_diplome integer,
    sexe integer,
    age integer
);


ALTER TABLE public.besoin OWNER TO postgres;

--
-- Name: besoin_id_besoin_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.besoin_id_besoin_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.besoin_id_besoin_seq OWNER TO postgres;

--
-- Name: besoin_id_besoin_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.besoin_id_besoin_seq OWNED BY public.besoin.id_besoin;


--
-- Name: champs_besoin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.champs_besoin (
    id_champs_besoin integer NOT NULL,
    champ character varying(100),
    resultat character varying(100),
    obligatoire boolean,
    id_besoin integer
);


ALTER TABLE public.champs_besoin OWNER TO postgres;

--
-- Name: champs_besoin_id_champs_besoin_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.champs_besoin_id_champs_besoin_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.champs_besoin_id_champs_besoin_seq OWNER TO postgres;

--
-- Name: champs_besoin_id_champs_besoin_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.champs_besoin_id_champs_besoin_seq OWNED BY public.champs_besoin.id_champs_besoin;


--
-- Name: contrat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contrat (
    id_contrat integer NOT NULL,
    debut date,
    duree integer,
    description character varying(200),
    id_personne integer
);


ALTER TABLE public.contrat OWNER TO postgres;

--
-- Name: contrat_id_contrat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contrat_id_contrat_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.contrat_id_contrat_seq OWNER TO postgres;

--
-- Name: contrat_id_contrat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contrat_id_contrat_seq OWNED BY public.contrat.id_contrat;


--
-- Name: critere_entretien; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.critere_entretien (
    id_critere integer NOT NULL,
    libelle character varying(50),
    barem integer
);


ALTER TABLE public.critere_entretien OWNER TO postgres;

--
-- Name: critere_entretien_id_critere_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.critere_entretien_id_critere_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.critere_entretien_id_critere_seq OWNER TO postgres;

--
-- Name: critere_entretien_id_critere_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.critere_entretien_id_critere_seq OWNED BY public.critere_entretien.id_critere;


--
-- Name: cv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cv (
    id_cv integer NOT NULL,
    date_reception timestamp without time zone,
    contact character varying(50),
    experience integer,
    adresse character varying(100),
    photo character varying(200),
    id_besoin integer,
    id_diplome integer,
    id_personne integer
);


ALTER TABLE public.cv OWNER TO postgres;

--
-- Name: cv_id_cv_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cv_id_cv_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cv_id_cv_seq OWNER TO postgres;

--
-- Name: cv_id_cv_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cv_id_cv_seq OWNED BY public.cv.id_cv;


--
-- Name: detail_entretien; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detail_entretien (
    id_detail integer NOT NULL,
    id_cv integer,
    id_critere integer,
    note integer
);


ALTER TABLE public.detail_entretien OWNER TO postgres;

--
-- Name: detail_entretien_id_detail_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detail_entretien_id_detail_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detail_entretien_id_detail_seq OWNER TO postgres;

--
-- Name: detail_entretien_id_detail_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detail_entretien_id_detail_seq OWNED BY public.detail_entretien.id_detail;


--
-- Name: diplome; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.diplome (
    id_diplome integer NOT NULL,
    libelle character varying(100),
    valeur integer
);


ALTER TABLE public.diplome OWNER TO postgres;

--
-- Name: diplome_id_diplome_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.diplome_id_diplome_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.diplome_id_diplome_seq OWNER TO postgres;

--
-- Name: diplome_id_diplome_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.diplome_id_diplome_seq OWNED BY public.diplome.id_diplome;


--
-- Name: etape; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etape (
    id_etape integer NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.etape OWNER TO postgres;

--
-- Name: etape_id_etape_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.etape_id_etape_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.etape_id_etape_seq OWNER TO postgres;

--
-- Name: etape_id_etape_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.etape_id_etape_seq OWNED BY public.etape.id_etape;


--
-- Name: filiere; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.filiere (
    id_filiere integer NOT NULL,
    libelle character varying(100),
    id_diplome integer
);


ALTER TABLE public.filiere OWNER TO postgres;

--
-- Name: filiere_id_filiere_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.filiere_id_filiere_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.filiere_id_filiere_seq OWNER TO postgres;

--
-- Name: filiere_id_filiere_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.filiere_id_filiere_seq OWNED BY public.filiere.id_filiere;


--
-- Name: historique_cv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.historique_cv (
    id_historique_cv integer NOT NULL,
    resultat integer,
    date_evaluation timestamp without time zone,
    date_resultat timestamp without time zone,
    id_cv integer,
    id_etape integer
);


ALTER TABLE public.historique_cv OWNER TO postgres;

--
-- Name: historique_cv_id_historique_cv_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.historique_cv_id_historique_cv_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.historique_cv_id_historique_cv_seq OWNER TO postgres;

--
-- Name: historique_cv_id_historique_cv_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.historique_cv_id_historique_cv_seq OWNED BY public.historique_cv.id_historique_cv;


--
-- Name: historique_personnel; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.historique_personnel (
    id_historique_personnel integer NOT NULL,
    date_historique date,
    id_personne integer,
    id_statut integer
);


ALTER TABLE public.historique_personnel OWNER TO postgres;

--
-- Name: historique_personnel_id_historique_personnel_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.historique_personnel_id_historique_personnel_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.historique_personnel_id_historique_personnel_seq OWNER TO postgres;

--
-- Name: historique_personnel_id_historique_personnel_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.historique_personnel_id_historique_personnel_seq OWNED BY public.historique_personnel.id_historique_personnel;


--
-- Name: personne; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personne (
    id_personne integer NOT NULL,
    nom character varying(100),
    prenom character varying(100),
    date_naissance date,
    email character varying(100),
    id_sexe integer
);


ALTER TABLE public.personne OWNER TO postgres;

--
-- Name: personne_id_personne_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personne_id_personne_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personne_id_personne_seq OWNER TO postgres;

--
-- Name: personne_id_personne_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personne_id_personne_seq OWNED BY public.personne.id_personne;


--
-- Name: poste; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.poste (
    id_poste integer NOT NULL,
    designation character varying(150)
);


ALTER TABLE public.poste OWNER TO postgres;

--
-- Name: poste_id_poste_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.poste_id_poste_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.poste_id_poste_seq OWNER TO postgres;

--
-- Name: poste_id_poste_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.poste_id_poste_seq OWNED BY public.poste.id_poste;


--
-- Name: question_qcm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_qcm (
    id_question_qcm integer NOT NULL,
    question character varying(200),
    id_reponse integer,
    point integer,
    id_besoin integer
);


ALTER TABLE public.question_qcm OWNER TO postgres;

--
-- Name: question_qcm_id_question_qcm_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_qcm_id_question_qcm_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.question_qcm_id_question_qcm_seq OWNER TO postgres;

--
-- Name: question_qcm_id_question_qcm_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_qcm_id_question_qcm_seq OWNED BY public.question_qcm.id_question_qcm;


--
-- Name: reponses_cv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reponses_cv (
    id_reponses_cv integer NOT NULL,
    id_reponses_qcm integer,
    id_question_qcm integer,
    id_cv integer
);


ALTER TABLE public.reponses_cv OWNER TO postgres;

--
-- Name: reponses_cv_id_reponses_cv_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reponses_cv_id_reponses_cv_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reponses_cv_id_reponses_cv_seq OWNER TO postgres;

--
-- Name: reponses_cv_id_reponses_cv_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reponses_cv_id_reponses_cv_seq OWNED BY public.reponses_cv.id_reponses_cv;


--
-- Name: reponses_qcm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reponses_qcm (
    id_reponses_qcm integer NOT NULL,
    reponse character varying(200),
    id_question_qcm integer
);


ALTER TABLE public.reponses_qcm OWNER TO postgres;

--
-- Name: reponses_qcm_id_reponses_qcm_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reponses_qcm_id_reponses_qcm_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reponses_qcm_id_reponses_qcm_seq OWNER TO postgres;

--
-- Name: reponses_qcm_id_reponses_qcm_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reponses_qcm_id_reponses_qcm_seq OWNED BY public.reponses_qcm.id_reponses_qcm;


--
-- Name: sexe; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sexe (
    id_sexe integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.sexe OWNER TO postgres;

--
-- Name: sexe_id_sexe_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sexe_id_sexe_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sexe_id_sexe_seq OWNER TO postgres;

--
-- Name: sexe_id_sexe_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sexe_id_sexe_seq OWNED BY public.sexe.id_sexe;


--
-- Name: statut; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.statut (
    id_statut integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.statut OWNER TO postgres;

--
-- Name: statut_id_statut_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.statut_id_statut_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.statut_id_statut_seq OWNER TO postgres;

--
-- Name: statut_id_statut_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.statut_id_statut_seq OWNED BY public.statut.id_statut;


--
-- Name: vue_cv_personne_score; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vue_cv_personne_score AS
 SELECT c.id_cv,
    p.id_personne,
    p.nom,
    p.prenom,
    p.date_naissance,
    s.libelle AS sexe,
    po.designation AS poste_souhaite,
    c.experience AS experience_candidat,
    d.libelle AS diplome,
    count(hc.id_historique_cv) AS nombre_etapes_passees,
    ( SELECT (sum(historique_cv.resultat) / 2)
           FROM public.historique_cv
          WHERE (historique_cv.id_cv = c.id_cv)) AS score_total,
    max(hc.date_evaluation) AS derniere_evaluation,
    max(
        CASE
            WHEN (hc.id_etape = 2) THEN hc.resultat
            ELSE NULL::integer
        END) AS note_qcm,
    max(
        CASE
            WHEN (hc.id_etape = 3) THEN hc.resultat
            ELSE NULL::integer
        END) AS note_entretien,
    string_agg(DISTINCT (e.libelle)::text, ', '::text ORDER BY (e.libelle)::text) AS etapes_passees
   FROM ((((((((public.cv c
     JOIN public.personne p ON ((c.id_personne = p.id_personne)))
     JOIN public.sexe s ON ((p.id_sexe = s.id_sexe)))
     JOIN public.diplome d ON ((c.id_diplome = d.id_diplome)))
     JOIN public.historique_personnel hp ON ((p.id_personne = hp.id_personne)))
     JOIN public.besoin b ON ((c.id_besoin = b.id_besoin)))
     JOIN public.poste po ON ((b.id_poste = po.id_poste)))
     LEFT JOIN public.historique_cv hc ON ((c.id_cv = hc.id_cv)))
     LEFT JOIN public.etape e ON ((hc.id_etape = e.id_etape)))
  WHERE (( SELECT max(hp2.id_statut) AS max
           FROM public.historique_personnel hp2
          WHERE (hp2.id_personne = p.id_personne)) = 1)
  GROUP BY c.id_cv, p.id_personne, p.nom, p.prenom, p.date_naissance, s.libelle, po.designation, c.experience, d.libelle;


ALTER VIEW public.vue_cv_personne_score OWNER TO postgres;

--
-- Name: annonce id_annonce; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.annonce ALTER COLUMN id_annonce SET DEFAULT nextval('public.annonce_id_annonce_seq'::regclass);


--
-- Name: barem id_barem; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.barem ALTER COLUMN id_barem SET DEFAULT nextval('public.barem_id_barem_seq'::regclass);


--
-- Name: besoin id_besoin; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.besoin ALTER COLUMN id_besoin SET DEFAULT nextval('public.besoin_id_besoin_seq'::regclass);


--
-- Name: champs_besoin id_champs_besoin; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.champs_besoin ALTER COLUMN id_champs_besoin SET DEFAULT nextval('public.champs_besoin_id_champs_besoin_seq'::regclass);


--
-- Name: contrat id_contrat; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat ALTER COLUMN id_contrat SET DEFAULT nextval('public.contrat_id_contrat_seq'::regclass);


--
-- Name: critere_entretien id_critere; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.critere_entretien ALTER COLUMN id_critere SET DEFAULT nextval('public.critere_entretien_id_critere_seq'::regclass);


--
-- Name: cv id_cv; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cv ALTER COLUMN id_cv SET DEFAULT nextval('public.cv_id_cv_seq'::regclass);


--
-- Name: detail_entretien id_detail; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_entretien ALTER COLUMN id_detail SET DEFAULT nextval('public.detail_entretien_id_detail_seq'::regclass);


--
-- Name: diplome id_diplome; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diplome ALTER COLUMN id_diplome SET DEFAULT nextval('public.diplome_id_diplome_seq'::regclass);


--
-- Name: etape id_etape; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etape ALTER COLUMN id_etape SET DEFAULT nextval('public.etape_id_etape_seq'::regclass);


--
-- Name: filiere id_filiere; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filiere ALTER COLUMN id_filiere SET DEFAULT nextval('public.filiere_id_filiere_seq'::regclass);


--
-- Name: historique_cv id_historique_cv; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_cv ALTER COLUMN id_historique_cv SET DEFAULT nextval('public.historique_cv_id_historique_cv_seq'::regclass);


--
-- Name: historique_personnel id_historique_personnel; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_personnel ALTER COLUMN id_historique_personnel SET DEFAULT nextval('public.historique_personnel_id_historique_personnel_seq'::regclass);


--
-- Name: personne id_personne; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personne ALTER COLUMN id_personne SET DEFAULT nextval('public.personne_id_personne_seq'::regclass);


--
-- Name: poste id_poste; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.poste ALTER COLUMN id_poste SET DEFAULT nextval('public.poste_id_poste_seq'::regclass);


--
-- Name: question_qcm id_question_qcm; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_qcm ALTER COLUMN id_question_qcm SET DEFAULT nextval('public.question_qcm_id_question_qcm_seq'::regclass);


--
-- Name: reponses_cv id_reponses_cv; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_cv ALTER COLUMN id_reponses_cv SET DEFAULT nextval('public.reponses_cv_id_reponses_cv_seq'::regclass);


--
-- Name: reponses_qcm id_reponses_qcm; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_qcm ALTER COLUMN id_reponses_qcm SET DEFAULT nextval('public.reponses_qcm_id_reponses_qcm_seq'::regclass);


--
-- Name: sexe id_sexe; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sexe ALTER COLUMN id_sexe SET DEFAULT nextval('public.sexe_id_sexe_seq'::regclass);


--
-- Name: statut id_statut; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statut ALTER COLUMN id_statut SET DEFAULT nextval('public.statut_id_statut_seq'::regclass);


--
-- Data for Name: annonce; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.annonce (id_annonce, id_besoin) FROM stdin;
1	2
\.


--
-- Data for Name: barem; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.barem (id_barem, libelle, valeur) FROM stdin;
1	faible	0.00
2	moyen	0.50
3	eleve	1.00
\.


--
-- Data for Name: besoin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.besoin (id_besoin, experience, mission, date_limite, id_poste, id_diplome, sexe, age) FROM stdin;
1	24	developper des applications web	2024-12-31 23:59:59	1	2	2	40
2	2	mission 1	2025-09-22 00:00:00	1	1	1	45
\.


--
-- Data for Name: champs_besoin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.champs_besoin (id_champs_besoin, champ, resultat, obligatoire, id_besoin) FROM stdin;
1	diplome	1	t	2
\.


--
-- Data for Name: contrat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contrat (id_contrat, debut, duree, description, id_personne) FROM stdin;
1	2025-09-22	6	CONTRAT DE TRAVAIL ├Ç DUR├ëE IND├ëTERMIN├ëE (CDI)	3
\.


--
-- Data for Name: critere_entretien; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.critere_entretien (id_critere, libelle, barem) FROM stdin;
1	Respect des horaires et ponctualite	2
2	Politesse et courtoisie	2
3	Presentation personnelle	2
4	Clarte de l expression orale	2
5	Ecoute active	2
6	Gestion du stress	2
7	Capacite a recevoir une critique	2
8	Motivation generale	2
9	Implication dans la vie d equipe	2
10	Respect des regles et consignes	2
\.


--
-- Data for Name: cv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cv (id_cv, date_reception, contact, experience, adresse, photo, id_besoin, id_diplome, id_personne) FROM stdin;
1	2024-06-01 10:00:00	0123456789	12	Antananarivo	/photos/jean.jpg	1	2	1
2	2024-06-02 11:00:00	0987654321	6	Antananarivo	/photos/marie.jpg	1	1	2
3	2025-09-22 00:00:00	+261 34 12 34 56	5	Antananarivo, Madagascar	public/images/Capture_d___cran_2025-09-21_145809.png	2	1	3
\.


--
-- Data for Name: detail_entretien; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detail_entretien (id_detail, id_cv, id_critere, note) FROM stdin;
1	3	1	2
2	3	2	2
3	3	3	2
4	3	4	2
5	3	5	2
6	3	6	2
7	3	7	2
8	3	8	2
9	3	9	2
10	3	10	2
\.


--
-- Data for Name: diplome; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.diplome (id_diplome, libelle, valeur) FROM stdin;
1	licence	1
2	master	2
3	doctorat	3
\.


--
-- Data for Name: etape; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.etape (id_etape, libelle) FROM stdin;
1	selection de dossier
2	examen QCM
3	entretien
\.


--
-- Data for Name: filiere; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.filiere (id_filiere, libelle, id_diplome) FROM stdin;
\.


--
-- Data for Name: historique_cv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.historique_cv (id_historique_cv, resultat, date_evaluation, date_resultat, id_cv, id_etape) FROM stdin;
1	1	2024-06-05 14:00:00	2024-06-10 09:00:00	2	1
2	0	2025-09-22 13:59:22.698029	2025-09-22 13:59:22.698029	3	1
3	18	2025-09-22 14:07:08.048129	2025-09-22 14:07:08.048129	3	2
4	20	2025-09-22 11:09:00	2025-09-22 11:09:00	3	3
\.


--
-- Data for Name: historique_personnel; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.historique_personnel (id_historique_personnel, date_historique, id_personne, id_statut) FROM stdin;
1	2024-01-19	1	1
2	2024-01-20	2	1
3	\N	3	1
4	2025-09-22	3	2
\.


--
-- Data for Name: personne; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personne (id_personne, nom, prenom, date_naissance, email, id_sexe) FROM stdin;
1	RAKOTO	Jean	2000-01-01	jean@test.com	1
2	RAKOTO	Marie	2000-01-01	marie@test.com	1
3	Rajao	Andry	1990-01-01	rajao.andry@email.com	1
\.


--
-- Data for Name: poste; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.poste (id_poste, designation) FROM stdin;
1	developpeur junior
2	comptable
3	directeur commercial
\.


--
-- Data for Name: question_qcm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_qcm (id_question_qcm, question, id_reponse, point, id_besoin) FROM stdin;
1	Quel est le role principal d un framework MVC ?	1	2	1
2	Quelle est la difference entre SQL et NoSQL ?	4	2	1
3	Comment optimiser la performance d une application web ?	7	2	1
4	Comment gerez-vous le travail en equipe ?	10	2	1
5	Quelle est l importance de la documentation dans un projet ?	13	2	1
6	Comment reagissez-vous face a un delai serre ?	16	2	1
7	Que faites-vous si vous decouvrez une erreur critique en production ?	19	2	1
8	Comment reagissez-vous a une critique de votre code ?	22	2	1
9	Que faites-vous si un coll┼águe ne respecte pas les standards du projet ?	25	2	1
10	Quel est le role principal d un framework MVC ?	10	2	2
11	Quelle est la difference entre SQL et NoSQL ?	13	2	2
12	Comment optimiser la performance d une application web ?	16	2	2
13	Comment gerez-vous le travail en equipe ?	19	2	2
14	Quelle est l importance de la documentation dans un projet ?	22	2	2
15	Comment reagissez-vous face a un delai serre ?	25	2	2
16	Que faites-vous si vous decouvrez une erreur critique en production ?	28	2	2
17	Comment reagissez-vous a une critique de votre code ?	31	2	2
18	Que faites-vous si un coll┼águe ne respecte pas les standards du projet ?	34	2	2
\.


--
-- Data for Name: reponses_cv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reponses_cv (id_reponses_cv, id_reponses_qcm, id_question_qcm, id_cv) FROM stdin;
1	28	10	3
2	31	11	3
3	34	12	3
4	37	13	3
5	40	14	3
6	43	15	3
7	46	16	3
8	49	17	3
9	52	18	3
\.


--
-- Data for Name: reponses_qcm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reponses_qcm (id_reponses_qcm, reponse, id_question_qcm) FROM stdin;
1	Separer la logique metier, la presentation et les donnees	1
2	Gerer la base de donnees	1
3	Creer des interfaces graphiques	1
4	SQL est relationnel, NoSQL est non relationnel	2
5	SQL est plus rapide	2
6	NoSQL ne g┼áre pas les donnees	2
7	Utiliser le cache, optimiser les requ╦åtes, minifier les ressources	3
8	Ajouter plus de serveurs	3
9	Augmenter la RAM	3
10	Communiquer et collaborer avec les membres	4
11	Travailler seul	4
12	Ignorer les autres	4
13	Facilite la maintenance et la transmission du projet	5
14	Permet de gagner du temps	5
15	N est pas utile	5
16	Prioriser les tãÆches et demander de l aide si besoin	6
17	Ignorer le delai	6
18	Faire tout soi-m╦åme	6
19	Informer l equipe et corriger rapidement	7
20	Ignorer l erreur	7
21	BlãÆmer un coll┼águe	7
22	Accepter et chercher a s ameliorer	8
23	Se vexer	8
24	Ignorer la critique	8
25	Discuter avec lui et rappeler les standards	9
26	Signaler au manager	9
27	Ignorer le probl┼áme	9
28	Separer la logique metier, la presentation et les donnees	10
29	Gerer la base de donnees	10
30	Creer des interfaces graphiques	10
31	SQL est relationnel, NoSQL est non relationnel	11
32	SQL est plus rapide	11
33	NoSQL ne g┼áre pas les donnees	11
34	Utiliser le cache, optimiser les requ╦åtes, minifier les ressources	12
35	Ajouter plus de serveurs	12
36	Augmenter la RAM	12
37	Communiquer et collaborer avec les membres	13
38	Travailler seul	13
39	Ignorer les autres	13
40	Facilite la maintenance et la transmission du projet	14
41	Permet de gagner du temps	14
42	N est pas utile	14
43	Prioriser les tãÆches et demander de l aide si besoin	15
44	Ignorer le delai	15
45	Faire tout soi-m╦åme	15
46	Informer l equipe et corriger rapidement	16
47	Ignorer l erreur	16
48	BlãÆmer un coll┼águe	16
49	Accepter et chercher a s ameliorer	17
50	Se vexer	17
51	Ignorer la critique	17
52	Discuter avec lui et rappeler les standards	18
53	Signaler au manager	18
54	Ignorer le probl┼áme	18
\.


--
-- Data for Name: sexe; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sexe (id_sexe, libelle) FROM stdin;
1	feminin
2	masculin
\.


--
-- Data for Name: statut; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.statut (id_statut, libelle) FROM stdin;
1	Candidat
2	Employer
3	Recale
4	Employe
\.


--
-- Name: annonce_id_annonce_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.annonce_id_annonce_seq', 1, true);


--
-- Name: barem_id_barem_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.barem_id_barem_seq', 3, true);


--
-- Name: besoin_id_besoin_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.besoin_id_besoin_seq', 2, true);


--
-- Name: champs_besoin_id_champs_besoin_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.champs_besoin_id_champs_besoin_seq', 1, true);


--
-- Name: contrat_id_contrat_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contrat_id_contrat_seq', 1, true);


--
-- Name: critere_entretien_id_critere_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.critere_entretien_id_critere_seq', 10, true);


--
-- Name: cv_id_cv_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cv_id_cv_seq', 3, true);


--
-- Name: detail_entretien_id_detail_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detail_entretien_id_detail_seq', 10, true);


--
-- Name: diplome_id_diplome_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.diplome_id_diplome_seq', 3, true);


--
-- Name: etape_id_etape_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etape_id_etape_seq', 3, true);


--
-- Name: filiere_id_filiere_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.filiere_id_filiere_seq', 1, false);


--
-- Name: historique_cv_id_historique_cv_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historique_cv_id_historique_cv_seq', 4, true);


--
-- Name: historique_personnel_id_historique_personnel_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historique_personnel_id_historique_personnel_seq', 4, true);


--
-- Name: personne_id_personne_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personne_id_personne_seq', 3, true);


--
-- Name: poste_id_poste_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.poste_id_poste_seq', 3, true);


--
-- Name: question_qcm_id_question_qcm_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_qcm_id_question_qcm_seq', 18, true);


--
-- Name: reponses_cv_id_reponses_cv_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reponses_cv_id_reponses_cv_seq', 9, true);


--
-- Name: reponses_qcm_id_reponses_qcm_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reponses_qcm_id_reponses_qcm_seq', 54, true);


--
-- Name: sexe_id_sexe_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sexe_id_sexe_seq', 2, true);


--
-- Name: statut_id_statut_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.statut_id_statut_seq', 4, true);


--
-- Name: annonce annonce_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.annonce
    ADD CONSTRAINT annonce_pkey PRIMARY KEY (id_annonce);


--
-- Name: barem barem_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.barem
    ADD CONSTRAINT barem_pkey PRIMARY KEY (id_barem);


--
-- Name: besoin besoin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.besoin
    ADD CONSTRAINT besoin_pkey PRIMARY KEY (id_besoin);


--
-- Name: champs_besoin champs_besoin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.champs_besoin
    ADD CONSTRAINT champs_besoin_pkey PRIMARY KEY (id_champs_besoin);


--
-- Name: contrat contrat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT contrat_pkey PRIMARY KEY (id_contrat);


--
-- Name: critere_entretien critere_entretien_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.critere_entretien
    ADD CONSTRAINT critere_entretien_pkey PRIMARY KEY (id_critere);


--
-- Name: cv cv_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cv
    ADD CONSTRAINT cv_pkey PRIMARY KEY (id_cv);


--
-- Name: detail_entretien detail_entretien_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_entretien
    ADD CONSTRAINT detail_entretien_pkey PRIMARY KEY (id_detail);


--
-- Name: diplome diplome_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diplome
    ADD CONSTRAINT diplome_pkey PRIMARY KEY (id_diplome);


--
-- Name: etape etape_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etape
    ADD CONSTRAINT etape_pkey PRIMARY KEY (id_etape);


--
-- Name: filiere filiere_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filiere
    ADD CONSTRAINT filiere_pkey PRIMARY KEY (id_filiere);


--
-- Name: historique_cv historique_cv_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_cv
    ADD CONSTRAINT historique_cv_pkey PRIMARY KEY (id_historique_cv);


--
-- Name: historique_personnel historique_personnel_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_personnel
    ADD CONSTRAINT historique_personnel_pkey PRIMARY KEY (id_historique_personnel);


--
-- Name: personne personne_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personne
    ADD CONSTRAINT personne_pkey PRIMARY KEY (id_personne);


--
-- Name: poste poste_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.poste
    ADD CONSTRAINT poste_pkey PRIMARY KEY (id_poste);


--
-- Name: question_qcm question_qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_qcm
    ADD CONSTRAINT question_qcm_pkey PRIMARY KEY (id_question_qcm);


--
-- Name: reponses_cv reponses_cv_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_cv
    ADD CONSTRAINT reponses_cv_pkey PRIMARY KEY (id_reponses_cv);


--
-- Name: reponses_qcm reponses_qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_qcm
    ADD CONSTRAINT reponses_qcm_pkey PRIMARY KEY (id_reponses_qcm);


--
-- Name: sexe sexe_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sexe
    ADD CONSTRAINT sexe_pkey PRIMARY KEY (id_sexe);


--
-- Name: statut statut_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statut
    ADD CONSTRAINT statut_pkey PRIMARY KEY (id_statut);


--
-- Name: annonce annonce_id_besoin_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.annonce
    ADD CONSTRAINT annonce_id_besoin_fkey FOREIGN KEY (id_besoin) REFERENCES public.besoin(id_besoin);


--
-- Name: besoin besoin_id_diplome_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.besoin
    ADD CONSTRAINT besoin_id_diplome_fkey FOREIGN KEY (id_diplome) REFERENCES public.diplome(id_diplome);


--
-- Name: besoin besoin_id_poste_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.besoin
    ADD CONSTRAINT besoin_id_poste_fkey FOREIGN KEY (id_poste) REFERENCES public.poste(id_poste);


--
-- Name: champs_besoin champs_besoin_id_besoin_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.champs_besoin
    ADD CONSTRAINT champs_besoin_id_besoin_fkey FOREIGN KEY (id_besoin) REFERENCES public.besoin(id_besoin);


--
-- Name: contrat contrat_id_personne_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT contrat_id_personne_fkey FOREIGN KEY (id_personne) REFERENCES public.personne(id_personne);


--
-- Name: cv cv_id_besoin_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cv
    ADD CONSTRAINT cv_id_besoin_fkey FOREIGN KEY (id_besoin) REFERENCES public.besoin(id_besoin);


--
-- Name: cv cv_id_diplome_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cv
    ADD CONSTRAINT cv_id_diplome_fkey FOREIGN KEY (id_diplome) REFERENCES public.diplome(id_diplome);


--
-- Name: cv cv_id_personne_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cv
    ADD CONSTRAINT cv_id_personne_fkey FOREIGN KEY (id_personne) REFERENCES public.personne(id_personne);


--
-- Name: detail_entretien detail_entretien_id_critere_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_entretien
    ADD CONSTRAINT detail_entretien_id_critere_fkey FOREIGN KEY (id_critere) REFERENCES public.critere_entretien(id_critere);


--
-- Name: detail_entretien detail_entretien_id_cv_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_entretien
    ADD CONSTRAINT detail_entretien_id_cv_fkey FOREIGN KEY (id_cv) REFERENCES public.cv(id_cv);


--
-- Name: filiere filiere_id_diplome_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filiere
    ADD CONSTRAINT filiere_id_diplome_fkey FOREIGN KEY (id_diplome) REFERENCES public.diplome(id_diplome);


--
-- Name: historique_cv historique_cv_id_cv_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_cv
    ADD CONSTRAINT historique_cv_id_cv_fkey FOREIGN KEY (id_cv) REFERENCES public.cv(id_cv);


--
-- Name: historique_cv historique_cv_id_etape_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_cv
    ADD CONSTRAINT historique_cv_id_etape_fkey FOREIGN KEY (id_etape) REFERENCES public.etape(id_etape);


--
-- Name: historique_personnel historique_personnel_id_personne_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_personnel
    ADD CONSTRAINT historique_personnel_id_personne_fkey FOREIGN KEY (id_personne) REFERENCES public.personne(id_personne);


--
-- Name: historique_personnel historique_personnel_id_statut_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historique_personnel
    ADD CONSTRAINT historique_personnel_id_statut_fkey FOREIGN KEY (id_statut) REFERENCES public.statut(id_statut);


--
-- Name: personne personne_id_sexe_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personne
    ADD CONSTRAINT personne_id_sexe_fkey FOREIGN KEY (id_sexe) REFERENCES public.sexe(id_sexe);


--
-- Name: question_qcm question_qcm_id_besoin_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_qcm
    ADD CONSTRAINT question_qcm_id_besoin_fkey FOREIGN KEY (id_besoin) REFERENCES public.besoin(id_besoin);


--
-- Name: reponses_cv reponses_cv_id_cv_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_cv
    ADD CONSTRAINT reponses_cv_id_cv_fkey FOREIGN KEY (id_cv) REFERENCES public.cv(id_cv);


--
-- Name: reponses_cv reponses_cv_id_question_qcm_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_cv
    ADD CONSTRAINT reponses_cv_id_question_qcm_fkey FOREIGN KEY (id_question_qcm) REFERENCES public.question_qcm(id_question_qcm);


--
-- Name: reponses_cv reponses_cv_id_reponses_qcm_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_cv
    ADD CONSTRAINT reponses_cv_id_reponses_qcm_fkey FOREIGN KEY (id_reponses_qcm) REFERENCES public.reponses_qcm(id_reponses_qcm);


--
-- Name: reponses_qcm reponses_qcm_id_question_qcm_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reponses_qcm
    ADD CONSTRAINT reponses_qcm_id_question_qcm_fkey FOREIGN KEY (id_question_qcm) REFERENCES public.question_qcm(id_question_qcm);


--
-- PostgreSQL database dump complete
--

