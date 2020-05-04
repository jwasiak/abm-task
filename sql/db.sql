CREATE TABLE "public"."persons" (
    "id" int4 NOT NULL,
    "name" text,
    "surname" text,
    "phone" text,
    "email" text,
    "notes" text,
    PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE
)
WITH (OIDS=FALSE);

CREATE SEQUENCE "public"."persons_seq" OWNED BY "persons"."id";

ALTER TABLE "public"."persons"
    ALTER COLUMN "id" SET DEFAULT nextval('persons_seq'::regclass);


CREATE TABLE "public"."spots" (
    "id" int4 NOT NULL,
    "mark" text,
    "descr" text,
    PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE
)
WITH (OIDS=FALSE);

CREATE SEQUENCE "public"."spots_seq" OWNED BY "spots"."id";

ALTER TABLE "public"."spots"
    ALTER COLUMN "id" SET DEFAULT nextval('spots_seq');


CREATE TABLE "public"."tools" (
    "id" int4 NOT NULL,
    "kind" text,
    "model" text,
    "mark" text,
    "year" int2,
    "value" numeric,
    "descr" text,
    PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE
)
WITH (OIDS=FALSE);

CREATE SEQUENCE "public"."tools_seq" OWNED BY "tools"."id";


ALTER TABLE "public"."tools"
    ALTER COLUMN "id" SET DEFAULT nextval('tools_seq');


CREATE TABLE "public"."spot_tools" (
    "id" int4 NOT NULL,
    "spot_id" int4 NOT NULL,
    "tool_id" int4 NOT NULL,
    PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE,
    CONSTRAINT "spot_id_fk" FOREIGN KEY ("spot_id") REFERENCES "public"."spots" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT "tool_id_fk" FOREIGN KEY ("tool_id") REFERENCES "public"."tools" ("id") ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS=FALSE);

CREATE SEQUENCE "public"."spot_tools_seq" OWNED BY "spot_tools"."id";

ALTER TABLE "public"."spot_tools"
    ALTER COLUMN "id" SET DEFAULT nextval('spot_tools_seq');

CREATE VIEW "public"."spot_tools_vw" AS
SELECT spot_tools.id, spot_id, tool_id, tools.model, tools.mark
FROM spot_tools
LEFT JOIN tools ON tool_id = tools.id;


CREATE TABLE "public"."bookings" (
    "id" int4 NOT NULL,
    "spot_id" int4 NOT NULL,
    "person_id" int4 NOT NULL,
    "start" timestamp NOT NULL,
    "stop" timestamp NOT NULL,
    PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE
)
WITH (OIDS=FALSE);


CREATE SEQUENCE "public"."bookings_seq" OWNED BY "bookings"."id";


ALTER TABLE "public"."bookings"
    ALTER COLUMN "id" SET DEFAULT nextval('bookings_seq');

ALTER TABLE "public"."bookings" ADD CONSTRAINT "person_id_fk" FOREIGN KEY ("person_id") REFERENCES "public"."persons" ("id") ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE "public"."bookings" ADD CONSTRAINT "spot_id_fk" FOREIGN KEY ("spot_id") REFERENCES "public"."spots" ("id") ON UPDATE RESTRICT ON DELETE RESTRICT;


CREATE VIEW "public"."bookings_vw" AS
SELECT bookings.id, bookings.start, bookings.stop, persons.name, persons.surname, spots.mark
FROM bookings
LEFT JOIN spots ON spots.id=spot_id
LEFT JOIN persons ON persons.id=person_id;
