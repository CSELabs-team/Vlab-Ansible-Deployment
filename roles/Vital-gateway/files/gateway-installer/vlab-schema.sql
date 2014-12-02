--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: vlab_interim; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA vlab_interim;


ALTER SCHEMA vlab_interim OWNER TO postgres;

SET search_path = vlab_interim, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: class; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE class (
    id integer NOT NULL,
    class character varying NOT NULL,
    name character varying NOT NULL,
    uid integer NOT NULL,
    registration_code integer DEFAULT 0 NOT NULL,
    enabled boolean DEFAULT false NOT NULL,
    deletep boolean DEFAULT false,
    restorep boolean DEFAULT false
);


ALTER TABLE vlab_interim.class OWNER TO postgres;

--
-- Name: class_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE class_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.class_id_seq OWNER TO postgres;

--
-- Name: class_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: postgres
--

ALTER SEQUENCE class_id_seq OWNED BY class.id;


--
-- Name: counter; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE counter (
    count integer,
    name text,
    base integer DEFAULT 0 NOT NULL,
    size integer DEFAULT 0 NOT NULL
);


ALTER TABLE vlab_interim.counter OWNER TO postgres;

--
-- Name: disk_images; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE disk_images (
    use character varying NOT NULL,
    vm_name_base character varying NOT NULL,
    conf_template character varying NOT NULL,
    backing_file character varying NOT NULL,
    ram integer NOT NULL,
    id integer NOT NULL,
    vcpu integer
);


ALTER TABLE vlab_interim.disk_images OWNER TO postgres;

--
-- Name: disk_images_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE disk_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.disk_images_id_seq OWNER TO postgres;

--
-- Name: disk_images_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: postgres
--

ALTER SEQUENCE disk_images_id_seq OWNED BY disk_images.id;


--
-- Name: email; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE email (
    fname text,
    lname text,
    user_id integer,
    email text,
    course_id integer
);


ALTER TABLE vlab_interim.email OWNER TO postgres;

--
-- Name: instructor; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE instructor (
    uid integer NOT NULL,
    fname character varying NOT NULL,
    lname character varying NOT NULL,
    pass character varying NOT NULL
);


ALTER TABLE vlab_interim.instructor OWNER TO postgres;

--
-- Name: lb_group; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE lb_group (
    group_id smallint,
    xen_server text
);


ALTER TABLE vlab_interim.lb_group OWNER TO postgres;

--
-- Name: lb_mode; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE lb_mode (
    mode smallint,
    description text,
    lb_group smallint
);


ALTER TABLE vlab_interim.lb_mode OWNER TO postgres;

--
-- Name: machines; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE machines (
    vm_base_name character varying NOT NULL,
    nic integer NOT NULL,
    net character varying NOT NULL,
    machine_num integer NOT NULL,
    student_num integer NOT NULL,
    class integer NOT NULL,
    mac character varying NOT NULL
);


ALTER TABLE vlab_interim.machines OWNER TO postgres;

--
-- Name: reflector_id; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE reflector_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.reflector_id OWNER TO postgres;

--
-- Name: reflector; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE reflector (
    reflector_id integer DEFAULT nextval('reflector_id'::regclass),
    reflector_port integer,
    reflector_pass text,
    vm_id integer
);


ALTER TABLE vlab_interim.reflector OWNER TO postgres;

--
-- Name: section_configs; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE section_configs (
    created timestamp without time zone DEFAULT now() NOT NULL,
    post_data character varying NOT NULL,
    saved_name character varying NOT NULL,
    id integer NOT NULL
);


ALTER TABLE vlab_interim.section_configs OWNER TO postgres;

--
-- Name: section_configs_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE section_configs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.section_configs_id_seq OWNER TO postgres;

--
-- Name: section_configs_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: postgres
--

ALTER SEQUENCE section_configs_id_seq OWNED BY section_configs.id;


--
-- Name: servers; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE servers (
    xen_server character varying(50),
    total_memory bigint
);


ALTER TABLE vlab_interim.servers OWNER TO postgres;

--
-- Name: sftp; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE sftp (
    user_id integer,
    sftp_user text,
    sftp_pass text
);


ALTER TABLE vlab_interim.sftp OWNER TO postgres;

--
-- Name: shutdown_exclude; Type: TABLE; Schema: vlab_interim; Owner: root; Tablespace: 
--

CREATE TABLE shutdown_exclude (
    vm_name character varying(50)
);


ALTER TABLE vlab_interim.shutdown_exclude OWNER TO root;

--
-- Name: student_numbers; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE student_numbers (
    base integer NOT NULL,
    size integer NOT NULL,
    class character varying NOT NULL
);


ALTER TABLE vlab_interim.student_numbers OWNER TO postgres;

--
-- Name: test1; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE test1 (
    a boolean,
    b text,
    c boolean,
    d boolean DEFAULT true,
    e boolean DEFAULT true
);


ALTER TABLE vlab_interim.test1 OWNER TO postgres;

--
-- Name: ur_id; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE ur_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.ur_id OWNER TO postgres;

--
-- Name: user_id; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE user_id
    START WITH 5000
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.user_id OWNER TO postgres;

--
-- Name: user; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE "user" (
    user_id integer DEFAULT nextval('user_id'::regclass),
    user_name text,
    user_pass text,
    email character varying(100),
    user_suffix character varying(10)
);


ALTER TABLE vlab_interim."user" OWNER TO postgres;

--
-- Name: user_reflector; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE user_reflector (
    user_id integer,
    reflector_id integer,
    ur_id integer DEFAULT nextval('ur_id'::regclass)
);


ALTER TABLE vlab_interim.user_reflector OWNER TO postgres;

--
-- Name: vbridge; Type: TABLE; Schema: vlab_interim; Owner: root; Tablespace: 
--

CREATE TABLE vbridge (
    brdg_id integer NOT NULL,
    brdg_name text
);


ALTER TABLE vlab_interim.vbridge OWNER TO root;

--
-- Name: vbridge_brdg_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: root
--

CREATE SEQUENCE vbridge_brdg_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.vbridge_brdg_id_seq OWNER TO root;

--
-- Name: vbridge_brdg_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: root
--

ALTER SEQUENCE vbridge_brdg_id_seq OWNED BY vbridge.brdg_id;


--
-- Name: vbridge_vlan; Type: TABLE; Schema: vlab_interim; Owner: root; Tablespace: 
--

CREATE TABLE vbridge_vlan (
    brdg_id integer,
    vlan_id integer,
    bif_id integer NOT NULL
);


ALTER TABLE vlab_interim.vbridge_vlan OWNER TO root;

--
-- Name: vbridge_vlan_bif_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: root
--

CREATE SEQUENCE vbridge_vlan_bif_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.vbridge_vlan_bif_id_seq OWNER TO root;

--
-- Name: vbridge_vlan_bif_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: root
--

ALTER SEQUENCE vbridge_vlan_bif_id_seq OWNED BY vbridge_vlan.bif_id;


--
-- Name: vif; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE vif (
    vm_id integer,
    vif_mac macaddr,
    vif_brdg_id integer,
    vif_name text
);


ALTER TABLE vlab_interim.vif OWNER TO postgres;

--
-- Name: vlan; Type: TABLE; Schema: vlab_interim; Owner: root; Tablespace: 
--

CREATE TABLE vlan (
    vlan_id integer,
    if_name text
);


ALTER TABLE vlab_interim.vlan OWNER TO root;

--
-- Name: vmid; Type: SEQUENCE; Schema: vlab_interim; Owner: postgres
--

CREATE SEQUENCE vmid
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.vmid OWNER TO postgres;

--
-- Name: vm_resource; Type: TABLE; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

CREATE TABLE vm_resource (
    xen_server text,
    requested_state integer,
    current_state integer,
    "timestamp" timestamp without time zone,
    vm_name text,
    username text,
    vm_id integer DEFAULT nextval('vmid'::regclass),
    vlan_id integer,
    vnc_port integer,
    memory integer,
    notes text,
    vm_friendly_name text,
    inprocess integer,
    disk_image character varying(100),
    reimage_file character varying(100),
    disabled integer,
    lb_mode smallint,
    createdp boolean DEFAULT false,
    vcpu integer
);


ALTER TABLE vlab_interim.vm_resource OWNER TO postgres;

--
-- Name: vnc_base; Type: TABLE; Schema: vlab_interim; Owner: root; Tablespace: 
--

CREATE TABLE vnc_base (
    vm_name_base character varying(25),
    vnc_base integer,
    conf_template character varying(40),
    course character varying(40),
    id integer NOT NULL,
    vm_friendly_name text,
    reimage_file text,
    backing_file text,
    memory integer,
    createdp boolean DEFAULT true NOT NULL,
    vcpu integer
);


ALTER TABLE vlab_interim.vnc_base OWNER TO root;

--
-- Name: vnc_base_id_seq; Type: SEQUENCE; Schema: vlab_interim; Owner: root
--

CREATE SEQUENCE vnc_base_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE vlab_interim.vnc_base_id_seq OWNER TO root;

--
-- Name: vnc_base_id_seq; Type: SEQUENCE OWNED BY; Schema: vlab_interim; Owner: root
--

ALTER SEQUENCE vnc_base_id_seq OWNED BY vnc_base.id;


--
-- Name: id; Type: DEFAULT; Schema: vlab_interim; Owner: postgres
--

ALTER TABLE class ALTER COLUMN id SET DEFAULT nextval('class_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: vlab_interim; Owner: postgres
--

ALTER TABLE disk_images ALTER COLUMN id SET DEFAULT nextval('disk_images_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: vlab_interim; Owner: postgres
--

ALTER TABLE section_configs ALTER COLUMN id SET DEFAULT nextval('section_configs_id_seq'::regclass);


--
-- Name: brdg_id; Type: DEFAULT; Schema: vlab_interim; Owner: root
--

ALTER TABLE vbridge ALTER COLUMN brdg_id SET DEFAULT nextval('vbridge_brdg_id_seq'::regclass);


--
-- Name: bif_id; Type: DEFAULT; Schema: vlab_interim; Owner: root
--

ALTER TABLE vbridge_vlan ALTER COLUMN bif_id SET DEFAULT nextval('vbridge_vlan_bif_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: vlab_interim; Owner: root
--

ALTER TABLE vnc_base ALTER COLUMN id SET DEFAULT nextval('vnc_base_id_seq'::regclass);


--
-- Name: class_pkey; Type: CONSTRAINT; Schema: vlab_interim; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY class
    ADD CONSTRAINT class_pkey PRIMARY KEY (id);


--
-- Name: vlan_vlan_id_key; Type: CONSTRAINT; Schema: vlab_interim; Owner: root; Tablespace: 
--

ALTER TABLE ONLY vlan
    ADD CONSTRAINT vlan_vlan_id_key UNIQUE (vlan_id);


--
-- PostgreSQL database dump complete
--

