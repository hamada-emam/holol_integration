CREATE TABLE IF NOT EXISTS "migrations" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "migration" VARCHAR NOT NULL,
    "batch" INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL,
    "username" VARCHAR NOT NULL,
    "email_verified_at" DATETIME,
    "password" VARCHAR NOT NULL,
    "remember_token" VARCHAR,
    "created_at" DATETIME,
    "updated_at" DATETIME
);

CREATE UNIQUE INDEX "users_username_unique" ON "users" ("username");

CREATE TABLE IF NOT EXISTS "password_reset_tokens" (
    "email" VARCHAR NOT NULL,
    "token" VARCHAR NOT NULL,
    "created_at" DATETIME,
    PRIMARY KEY ("email")
);

CREATE TABLE IF NOT EXISTS "failed_jobs" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "uuid" VARCHAR NOT NULL,
    "connection" TEXT NOT NULL,
    "queue" TEXT NOT NULL,
    "payload" TEXT NOT NULL,
    "exception" TEXT NOT NULL,
    "failed_at" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX "failed_jobs_uuid_unique" ON "failed_jobs" ("uuid");

CREATE TABLE IF NOT EXISTS "personal_access_tokens" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "tokenable_type" VARCHAR NOT NULL,
    "tokenable_id" INTEGER NOT NULL,
    "name" VARCHAR NOT NULL,
    "token" VARCHAR NOT NULL,
    "abilities" TEXT,
    "last_used_at" DATETIME,
    "expires_at" DATETIME,
    "created_at" DATETIME,
    "updated_at" DATETIME
);

CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens" ("tokenable_type", "tokenable_id");

CREATE UNIQUE INDEX "personal_access_tokens_token_unique" ON "personal_access_tokens" ("token");

CREATE TABLE IF NOT EXISTS "shipments" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "shipment" TEXT NOT NULL,
    "shipment_id" INTEGER NOT NULL,
    "order_code" INTEGER,
    "created_at" DATETIME,
    "updated_at" DATETIME
);

CREATE TABLE IF NOT EXISTS "jobs" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "queue" VARCHAR NOT NULL,
    "payload" TEXT NOT NULL,
    "attempts" INTEGER NOT NULL,
    "reserved_at" INTEGER,
    "available_at" INTEGER NOT NULL,
    "created_at" INTEGER NOT NULL
);

CREATE INDEX "jobs_queue_index" ON "jobs" ("queue");

CREATE TABLE IF NOT EXISTS "provinies" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL
);

CREATE TABLE IF NOT EXISTS "cities" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL,
    "province_name" VARCHAR NOT NULL
);

CREATE TABLE IF NOT EXISTS "areas" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL,
    "city_name" VARCHAR NOT NULL
);

CREATE TABLE IF NOT EXISTS "settings" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "token" VARCHAR NOT NULL,
    "url" VARCHAR NOT NULL,
    "type_code" VARCHAR NOT NULL
);

CREATE TABLE IF NOT EXISTS "zones" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "zone_id" INTEGER NOT NULL,
    "mapped_zone" VARCHAR,
    "parent_id" INTEGER
);

CREATE TABLE IF NOT EXISTS "clients" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "company_code" VARCHAR NOT NULL,
    "client_code" VARCHAR NOT NULL,
    "type_code" VARCHAR NOT NULL,
    "url" VARCHAR NOT NULL,
    "token" VARCHAR NOT NULL,
    "secret_key" VARCHAR NOT NULL,
    UNIQUE ("client_code", "type_code"),
    UNIQUE ("company_code", "client_code", "type_code"),
);

INSERT INTO "clients" ('company_code','client_code', 'type_code', 'url', 'token')
    SELECT 'HOLOL','IMILE', "type_code", "url", "token" FROM "settings";

INSERT INTO MIGRATIONS VALUES(
    1,
    '2014_10_12_000000_create_users_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    2,
    '2014_10_12_100000_create_password_reset_tokens_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    3,
    '2019_08_19_000000_create_failed_jobs_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    4,
    '2019_12_14_000001_create_personal_access_tokens_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    5,
    '2023_07_13_093127_create_shipment_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    7,
    '2023_07_16_063858_create_jobs_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    8,
    '2023_07_16_093244_create_provinies_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    9,
    '2023_07_16_093246_create_cities_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    10,
    '2023_07_16_093247_create_areas_table',
    1
);

INSERT INTO MIGRATIONS VALUES(
    11,
    '2023_07_20_093250_create_settings_table',
    2
);

INSERT INTO MIGRATIONS VALUES(
    12,
    '2023_07_13_093151_create_zones_table',
    3
);

INSERT INTO MIGRATIONS VALUES(
    13,
    '2023_08_07_093251_create_clients_table',
    4
);