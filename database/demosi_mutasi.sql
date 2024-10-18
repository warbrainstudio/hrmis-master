--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.25
-- Dumped by pg_dump version 9.3.25
-- Started on 2023-08-18 00:25:31

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 226 (class 1259 OID 61125)
-- Name: demosi_mutasi; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE public.demosi_mutasi (
    id bigint NOT NULL,
    pegawai_id bigint NOT NULL,
    kategori character varying(10) NOT NULL,
    no_sk character varying(50) NOT NULL,
    tanggal_sk date NOT NULL,
    no_skppj character varying(50) NOT NULL,
    doj date NOT NULL,
    kode_pendidikan character varying(10),
    old_unit_id bigint NOT NULL,
    old_sub_unit_id bigint NOT NULL,
    old_jabatan_id bigint NOT NULL,
    old_tenaga_unit_id bigint NOT NULL,
    old_jenis_pegawai_id bigint NOT NULL,
    new_unit_id bigint NOT NULL,
    new_sub_unit_id bigint NOT NULL,
    new_jabatan_id bigint NOT NULL,
    new_tenaga_unit_id bigint NOT NULL,
    new_jenis_pegawai_id bigint NOT NULL,
    status_active integer DEFAULT 1 NOT NULL,
    created_by bigint,
    created_date timestamp without time zone DEFAULT now(),
    updated_by bigint,
    updated_date timestamp without time zone
);


--
-- TOC entry 2101 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN demosi_mutasi.kategori; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.demosi_mutasi.kategori IS 'Demosi, Mutasi';


--
-- TOC entry 2102 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN demosi_mutasi.kode_pendidikan; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.demosi_mutasi.kode_pendidikan IS 'Status Kerja';


--
-- TOC entry 2103 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN demosi_mutasi.status_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.demosi_mutasi.status_active IS 'Saat ini belum digunakan';


--
-- TOC entry 225 (class 1259 OID 61123)
-- Name: demosi_mutasi_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.demosi_mutasi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2104 (class 0 OID 0)
-- Dependencies: 225
-- Name: demosi_mutasi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.demosi_mutasi_id_seq OWNED BY public.demosi_mutasi.id;


--
-- TOC entry 1980 (class 2604 OID 61128)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.demosi_mutasi ALTER COLUMN id SET DEFAULT nextval('public.demosi_mutasi_id_seq'::regclass);


--
-- TOC entry 2095 (class 0 OID 61125)
-- Dependencies: 226
-- Data for Name: demosi_mutasi; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.demosi_mutasi (id, pegawai_id, kategori, no_sk, tanggal_sk, no_skppj, doj, kode_pendidikan, old_unit_id, old_sub_unit_id, old_jabatan_id, old_tenaga_unit_id, old_jenis_pegawai_id, new_unit_id, new_sub_unit_id, new_jabatan_id, new_tenaga_unit_id, new_jenis_pegawai_id, status_active, created_by, created_date, updated_by, updated_date) FROM stdin;
1	1	Demosi	SKD-01	2023-07-01	SKPPJ-01	2023-07-10	\N	1	4	8	1	4	1	34	1	1	4	1	1	2023-08-17 19:59:05.902	1	2023-08-18 00:17:50
3	1	Mutasi	SKM-01	2023-08-21	SKPPJ-02	2023-09-01	\N	1	4	8	1	4	7	48	4	1	4	1	1	2023-08-17 21:12:21.628	1	2023-08-18 00:18:55
4	1	Mutasi	SKM-02	2023-08-18	SKPPJ-03	2023-09-04	\N	7	48	4	1	4	1	4	8	1	4	1	1	2023-08-18 00:20:33.813	\N	\N
\.


--
-- TOC entry 2105 (class 0 OID 0)
-- Dependencies: 225
-- Name: demosi_mutasi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.demosi_mutasi_id_seq', 4, true);


--
-- TOC entry 1984 (class 2606 OID 61131)
-- Name: demosi_mutasi_pk; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY public.demosi_mutasi
    ADD CONSTRAINT demosi_mutasi_pk PRIMARY KEY (id);


-- Completed on 2023-08-18 00:25:31

--
-- PostgreSQL database dump complete
--

