/*
 Navicat Premium Data Transfer

 Source Server         : Vlab
 Source Server Type    : PostgreSQL
 Source Server Version : 90113
 Source Host           : 128.238.66.20
 Source Database       : vlab
 Source Schema         : vlab_interim

 Target Server Type    : PostgreSQL
 Target Server Version : 90113
 File Encoding         : utf-8

 Date: 11/29/2014 23:50:57 PM
*/

-- ----------------------------
--  Sequence structure for class_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."class_id_seq";
CREATE SEQUENCE "vlab_interim"."class_id_seq" INCREMENT 1 START 31 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."class_id_seq" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for disk_images_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."disk_images_id_seq";
CREATE SEQUENCE "vlab_interim"."disk_images_id_seq" INCREMENT 1 START 19 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."disk_images_id_seq" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for reflector_id
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."reflector_id";
CREATE SEQUENCE "vlab_interim"."reflector_id" INCREMENT 1 START 18515 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."reflector_id" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for section_configs_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."section_configs_id_seq";
CREATE SEQUENCE "vlab_interim"."section_configs_id_seq" INCREMENT 1 START 33 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."section_configs_id_seq" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for ur_id
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."ur_id";
CREATE SEQUENCE "vlab_interim"."ur_id" INCREMENT 1 START 18697 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."ur_id" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for user_id
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."user_id";
CREATE SEQUENCE "vlab_interim"."user_id" INCREMENT 1 START 7567 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."user_id" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for vbridge_brdg_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."vbridge_brdg_id_seq";
CREATE SEQUENCE "vlab_interim"."vbridge_brdg_id_seq" INCREMENT 1 START 18892 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."vbridge_brdg_id_seq" OWNER TO "root";

-- ----------------------------
--  Sequence structure for vbridge_vlan_bif_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."vbridge_vlan_bif_id_seq";
CREATE SEQUENCE "vlab_interim"."vbridge_vlan_bif_id_seq" INCREMENT 1 START 10 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."vbridge_vlan_bif_id_seq" OWNER TO "root";

-- ----------------------------
--  Sequence structure for vmid
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."vmid";
CREATE SEQUENCE "vlab_interim"."vmid" INCREMENT 1 START 31755 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."vmid" OWNER TO "postgres";

-- ----------------------------
--  Sequence structure for vnc_base_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "vlab_interim"."vnc_base_id_seq";
CREATE SEQUENCE "vlab_interim"."vnc_base_id_seq" INCREMENT 1 START 129 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;
ALTER TABLE "vlab_interim"."vnc_base_id_seq" OWNER TO "root";

-- ----------------------------
--  Table structure for vbridge_vlan
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vbridge_vlan";
CREATE TABLE "vlab_interim"."vbridge_vlan" (
	"brdg_id" int4,
	"vlan_id" int4,
	"bif_id" int4 NOT NULL DEFAULT nextval('vbridge_vlan_bif_id_seq'::regclass)
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vbridge_vlan" OWNER TO "root";

-- ----------------------------
--  Table structure for sftp
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."sftp";
CREATE TABLE "vlab_interim"."sftp" (
	"user_id" int4,
	"sftp_user" text COLLATE "default",
	"sftp_pass" text COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."sftp" OWNER TO "postgres";

-- ----------------------------
--  Table structure for vif
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vif";
CREATE TABLE "vlab_interim"."vif" (
	"vm_id" int4,
	"vif_mac" macaddr,
	"vif_brdg_id" int4,
	"vif_name" text COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vif" OWNER TO "postgres";

-- ----------------------------
--  Table structure for machines
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."machines";
CREATE TABLE "vlab_interim"."machines" (
	"vm_base_name" varchar NOT NULL COLLATE "default",
	"nic" int4 NOT NULL,
	"net" varchar NOT NULL COLLATE "default",
	"machine_num" int4 NOT NULL,
	"student_num" int4 NOT NULL,
	"class" int4 NOT NULL,
	"mac" varchar NOT NULL COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."machines" OWNER TO "postgres";

-- ----------------------------
--  Table structure for user_reflector
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."user_reflector";
CREATE TABLE "vlab_interim"."user_reflector" (
	"user_id" int4,
	"reflector_id" int4,
	"ur_id" int4 DEFAULT nextval('ur_id'::regclass)
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."user_reflector" OWNER TO "postgres";

-- ----------------------------
--  Table structure for test1
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."test1";
CREATE TABLE "vlab_interim"."test1" (
	"a" bool,
	"b" text COLLATE "default",
	"c" bool,
	"d" bool DEFAULT true,
	"e" bool DEFAULT true
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."test1" OWNER TO "postgres";

-- ----------------------------
--  Table structure for vnc_base
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vnc_base";
CREATE TABLE "vlab_interim"."vnc_base" (
	"vm_name_base" varchar(25) COLLATE "default",
	"vnc_base" int4,
	"conf_template" varchar(40) COLLATE "default",
	"course" varchar(40) COLLATE "default",
	"id" int4 NOT NULL DEFAULT nextval('vnc_base_id_seq'::regclass),
	"vm_friendly_name" text COLLATE "default",
	"reimage_file" text COLLATE "default",
	"backing_file" text COLLATE "default",
	"memory" int4,
	"createdp" bool NOT NULL DEFAULT true,
	"vcpu" int4
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vnc_base" OWNER TO "root";

-- ----------------------------
--  Table structure for section_configs
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."section_configs";
CREATE TABLE "vlab_interim"."section_configs" (
	"created" timestamp(6) NOT NULL DEFAULT now(),
	"post_data" varchar NOT NULL COLLATE "default",
	"saved_name" varchar NOT NULL COLLATE "default",
	"id" int4 NOT NULL DEFAULT nextval('section_configs_id_seq'::regclass)
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."section_configs" OWNER TO "postgres";

-- ----------------------------
--  Table structure for student_numbers
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."student_numbers";
CREATE TABLE "vlab_interim"."student_numbers" (
	"base" int4 NOT NULL,
	"size" int4 NOT NULL,
	"class" varchar NOT NULL COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."student_numbers" OWNER TO "postgres";

-- ----------------------------
--  Table structure for vbridge
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vbridge";
CREATE TABLE "vlab_interim"."vbridge" (
	"brdg_id" int4 NOT NULL DEFAULT nextval('vbridge_brdg_id_seq'::regclass),
	"brdg_name" text COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vbridge" OWNER TO "root";

-- ----------------------------
--  Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."user";
CREATE TABLE "vlab_interim"."user" (
	"user_id" int4 DEFAULT nextval('user_id'::regclass),
	"user_name" text COLLATE "default",
	"user_pass" text COLLATE "default",
	"email" varchar(100) COLLATE "default",
	"user_suffix" varchar(10) COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."user" OWNER TO "postgres";

-- ----------------------------
--  Table structure for lb_mode
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."lb_mode";
CREATE TABLE "vlab_interim"."lb_mode" (
	"mode" int2,
	"description" text COLLATE "default",
	"lb_group" int2
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."lb_mode" OWNER TO "postgres";

-- ----------------------------
--  Table structure for reflector
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."reflector";
CREATE TABLE "vlab_interim"."reflector" (
	"reflector_id" int4 DEFAULT nextval('reflector_id'::regclass),
	"reflector_port" int4,
	"reflector_pass" text COLLATE "default",
	"vm_id" int4
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."reflector" OWNER TO "postgres";

-- ----------------------------
--  Table structure for servers
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."servers";
CREATE TABLE "vlab_interim"."servers" (
	"xen_server" varchar(50) COLLATE "default",
	"total_memory" int8
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."servers" OWNER TO "postgres";

-- ----------------------------
--  Table structure for class
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."class";
CREATE TABLE "vlab_interim"."class" (
	"id" int4 NOT NULL DEFAULT nextval('class_id_seq'::regclass),
	"class" varchar NOT NULL COLLATE "default",
	"name" varchar NOT NULL COLLATE "default",
	"uid" int4 NOT NULL,
	"registration_code" int4 NOT NULL DEFAULT 0,
	"enabled" bool NOT NULL DEFAULT false,
	"deletep" bool DEFAULT false,
	"restorep" bool DEFAULT false
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."class" OWNER TO "postgres";

-- ----------------------------
--  Table structure for lb_group
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."lb_group";
CREATE TABLE "vlab_interim"."lb_group" (
	"group_id" int2,
	"xen_server" text COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."lb_group" OWNER TO "postgres";

-- ----------------------------
--  Table structure for disk_images
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."disk_images";
CREATE TABLE "vlab_interim"."disk_images" (
	"use" varchar NOT NULL COLLATE "default",
	"vm_name_base" varchar NOT NULL COLLATE "default",
	"conf_template" varchar NOT NULL COLLATE "default",
	"backing_file" varchar NOT NULL COLLATE "default",
	"ram" int4 NOT NULL,
	"id" int4 NOT NULL DEFAULT nextval('disk_images_id_seq'::regclass),
	"vcpu" int4
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."disk_images" OWNER TO "postgres";

-- ----------------------------
--  Table structure for instructor
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."instructor";
CREATE TABLE "vlab_interim"."instructor" (
	"uid" int4 NOT NULL,
	"fname" varchar NOT NULL COLLATE "default",
	"lname" varchar NOT NULL COLLATE "default",
	"pass" varchar NOT NULL COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."instructor" OWNER TO "postgres";

-- ----------------------------
--  Table structure for shutdown_exclude
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."shutdown_exclude";
CREATE TABLE "vlab_interim"."shutdown_exclude" (
	"vm_name" varchar(50) COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."shutdown_exclude" OWNER TO "root";

-- ----------------------------
--  Table structure for vm_resource
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vm_resource";
CREATE TABLE "vlab_interim"."vm_resource" (
	"xen_server" text COLLATE "default",
	"requested_state" int4,
	"current_state" int4,
	"timestamp" timestamp(6) NULL,
	"vm_name" text COLLATE "default",
	"username" text COLLATE "default",
	"vm_id" int4 DEFAULT nextval('vmid'::regclass),
	"vlan_id" int4,
	"vnc_port" int4,
	"memory" int4,
	"notes" text COLLATE "default",
	"vm_friendly_name" text COLLATE "default",
	"inprocess" int4,
	"disk_image" varchar(100) COLLATE "default",
	"reimage_file" varchar(100) COLLATE "default",
	"disabled" int4,
	"lb_mode" int2,
	"createdp" bool DEFAULT false,
	"vcpu" int4
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vm_resource" OWNER TO "postgres";

-- ----------------------------
--  Table structure for email
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."email";
CREATE TABLE "vlab_interim"."email" (
	"fname" text COLLATE "default",
	"lname" text COLLATE "default",
	"user_id" int4,
	"email" text COLLATE "default",
	"course_id" int4
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."email" OWNER TO "postgres";

-- ----------------------------
--  Table structure for vlan
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."vlan";
CREATE TABLE "vlab_interim"."vlan" (
	"vlan_id" int4,
	"if_name" text COLLATE "default"
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."vlan" OWNER TO "root";

-- ----------------------------
--  Table structure for counter
-- ----------------------------
DROP TABLE IF EXISTS "vlab_interim"."counter";
CREATE TABLE "vlab_interim"."counter" (
	"count" int4,
	"name" text COLLATE "default",
	"base" int4 NOT NULL DEFAULT 0,
	"size" int4 NOT NULL DEFAULT 0
)
WITH (OIDS=FALSE);
ALTER TABLE "vlab_interim"."counter" OWNER TO "postgres";


-- ----------------------------
--  Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "vlab_interim"."class_id_seq" RESTART 32 OWNED BY "class"."id";
ALTER SEQUENCE "vlab_interim"."disk_images_id_seq" RESTART 20 OWNED BY "disk_images"."id";
ALTER SEQUENCE "vlab_interim"."reflector_id" RESTART 18516;
ALTER SEQUENCE "vlab_interim"."section_configs_id_seq" RESTART 34 OWNED BY "section_configs"."id";
ALTER SEQUENCE "vlab_interim"."ur_id" RESTART 18698;
ALTER SEQUENCE "vlab_interim"."user_id" RESTART 7568;
ALTER SEQUENCE "vlab_interim"."vbridge_brdg_id_seq" RESTART 18893 OWNED BY "vbridge"."brdg_id";
ALTER SEQUENCE "vlab_interim"."vbridge_vlan_bif_id_seq" RESTART 11 OWNED BY "vbridge_vlan"."bif_id";
ALTER SEQUENCE "vlab_interim"."vmid" RESTART 31756;
ALTER SEQUENCE "vlab_interim"."vnc_base_id_seq" RESTART 130 OWNED BY "vnc_base"."id";
-- ----------------------------
--  Primary key structure for table class
-- ----------------------------
ALTER TABLE "vlab_interim"."class" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE;

-- ----------------------------
--  Uniques structure for table vlan
-- ----------------------------
ALTER TABLE "vlab_interim"."vlan" ADD CONSTRAINT "vlan_vlan_id_key" UNIQUE ("vlan_id") NOT DEFERRABLE INITIALLY IMMEDIATE;

