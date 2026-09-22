-- CourtPass database schema (schema-first rule)
-- Tables are added here only after the team agrees on them.
-- Enum values are locked and shared across all modules.
-- The old prototype schema is in legacy/schema/courtpass.sql for reference only
-- (it has fields the revised proposal dropped, e.g. NIC upload and Parent/Player tags).

CREATE DATABASE IF NOT EXISTS courtpass
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE courtpass;
