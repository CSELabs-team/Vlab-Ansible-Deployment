--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = vlab_interim, pg_catalog;

--
-- Name: class_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('class_id_seq', 10, true);


--
-- Name: disk_images_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('disk_images_id_seq', 10, true);


--
-- Name: reflector_id; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('reflector_id', 1000, true);


--
-- Name: section_configs_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('section_configs_id_seq', 10, true);


--
-- Name: ur_id; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('ur_id', 1000, true);


--
-- Name: user_id; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('user_id', 5000, false);


--
-- Name: vbridge_brdg_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: root
--

SELECT pg_catalog.setval('vbridge_brdg_id_seq', 10004, true);


--
-- Name: vbridge_vlan_bif_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: root
--

SELECT pg_catalog.setval('vbridge_vlan_bif_id_seq', 5, true);


--
-- Name: vmid; Type: SEQUENCE SET; Schema: vlab_interim; Owner: postgres
--

SELECT pg_catalog.setval('vmid', 1000, true);


--
-- Name: vnc_base_id_seq; Type: SEQUENCE SET; Schema: vlab_interim; Owner: root
--

SELECT pg_catalog.setval('vnc_base_id_seq', 10, true);


--
-- Data for Name: class; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY class (id, class, name, uid, registration_code, enabled, deletep, restorep) FROM stdin;
\.


--
-- Data for Name: counter; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY counter (count, name, base, size) FROM stdin;
\.


--
-- Data for Name: disk_images; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY disk_images (use, vm_name_base, conf_template, backing_file, ram, id, vcpu) FROM stdin;
\.


--
-- Data for Name: email; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY email (fname, lname, user_id, email, course_id) FROM stdin;
\.


--
-- Data for Name: instructor; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY instructor (uid, fname, lname, pass) FROM stdin;
0	Test	Instructor	---
\.


--
-- Data for Name: lb_group; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY lb_group (group_id, xen_server) FROM stdin;
1	Vlab-xen1
\.


--
-- Data for Name: lb_mode; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY lb_mode (mode, description, lb_group) FROM stdin;
0	None	0
1	Resource avail. w/ cluster 1	1
2	Resource avail. w/ cluster 2	2
\.


--
-- Data for Name: machines; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY machines (vm_base_name, nic, net, machine_num, student_num, class, mac) FROM stdin;
\.


--
-- Data for Name: reflector; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY reflector (reflector_id, reflector_port, reflector_pass, vm_id) FROM stdin;
\.


--
-- Data for Name: section_configs; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY section_configs (created, post_data, saved_name, id) FROM stdin;
\.


--
-- Data for Name: servers; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY servers (xen_server, total_memory) FROM stdin;
Vlab-xen1	12000000000
\.


--
-- Data for Name: sftp; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY sftp (user_id, sftp_user, sftp_pass) FROM stdin;
\.


--
-- Data for Name: shutdown_exclude; Type: TABLE DATA; Schema: vlab_interim; Owner: root
--

COPY shutdown_exclude (vm_name) FROM stdin;
\.


--
-- Data for Name: student_numbers; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY student_numbers (base, size, class) FROM stdin;
\.


--
-- Data for Name: test1; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY test1 (a, b, c, d, e) FROM stdin;
t	t	t	t	t
f	f	f	f	t
f	f	f	f	t
f	f	f	f	t
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY "user" (user_id, user_name, user_pass, email, user_suffix) FROM stdin;
\.


--
-- Data for Name: user_reflector; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY user_reflector (user_id, reflector_id, ur_id) FROM stdin;
\.


--
-- Data for Name: vbridge; Type: TABLE DATA; Schema: vlab_interim; Owner: root
--

COPY vbridge (brdg_id, brdg_name) FROM stdin;
\.


--
-- Data for Name: vbridge_vlan; Type: TABLE DATA; Schema: vlab_interim; Owner: root
--

COPY vbridge_vlan (brdg_id, vlan_id, bif_id) FROM stdin;
\.


--
-- Data for Name: vif; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY vif (vm_id, vif_mac, vif_brdg_id, vif_name) FROM stdin;
\.


--
-- Data for Name: vlan; Type: TABLE DATA; Schema: vlab_interim; Owner: root
--

COPY vlan (vlan_id, if_name) FROM stdin;
\.


--
-- Data for Name: vm_resource; Type: TABLE DATA; Schema: vlab_interim; Owner: postgres
--

COPY vm_resource (xen_server, requested_state, current_state, "timestamp", vm_name, username, vm_id, vlan_id, vnc_port, memory, notes, vm_friendly_name, inprocess, disk_image, reimage_file, disabled, lb_mode, createdp, vcpu) FROM stdin;
\.


--
-- PostgreSQL database dump complete
--

