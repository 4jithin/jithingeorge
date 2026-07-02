-- Growth Clarity Co. — MySQL schema
-- Import with: mysql -u root -p growth_clarity < sql/schema.sql

CREATE DATABASE IF NOT EXISTS growth_clarity
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE growth_clarity;

-- One row per completed diagnostic (gate form + AI/fallback report).
CREATE TABLE IF NOT EXISTS diagnostic_submissions (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name               VARCHAR(150)      NOT NULL,
  email              VARCHAR(190)      NOT NULL,
  company            VARCHAR(150)      NULL,

  motion             VARCHAR(20)       NOT NULL,
  arr                VARCHAR(10)       NOT NULL,
  team               VARCHAR(10)       NOT NULL,
  messaging          VARCHAR(20)       NOT NULL,
  pipeline           VARCHAR(20)       NOT NULL,
  cac                VARCHAR(20)       NOT NULL,
  alignment          VARCHAR(20)       NOT NULL,
  conversion         VARCHAR(20)       NOT NULL,
  nrr                VARCHAR(20)       NOT NULL,
  freetext           VARCHAR(220)      NULL,

  score_positioning  TINYINT UNSIGNED  NOT NULL,
  score_demand       TINYINT UNSIGNED  NOT NULL,
  score_cac          TINYINT UNSIGNED  NOT NULL,
  score_alignment    TINYINT UNSIGNED  NOT NULL,
  score_conversion   TINYINT UNSIGNED  NOT NULL,
  score_expansion    TINYINT UNSIGNED  NOT NULL,
  health_score       TINYINT UNSIGNED  NOT NULL,
  top_cluster        VARCHAR(20)       NOT NULL,
  top_severity       TINYINT UNSIGNED  NOT NULL,
  suggested_tier     VARCHAR(20)       NOT NULL,

  ai_generated       TINYINT(1)        NOT NULL DEFAULT 0,
  client_report      JSON              NOT NULL,
  internal_brief     JSON              NOT NULL,

  ip_address         VARCHAR(45)       NULL,
  created_at         TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_email (email),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One row per claim.html submission (pre-diagnostic lead capture).
CREATE TABLE IF NOT EXISTS claim_submissions (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(150)  NOT NULL,
  email        VARCHAR(190)  NOT NULL,
  company      VARCHAR(150)  NOT NULL,
  role         VARCHAR(20)   NOT NULL,
  arr          VARCHAR(10)   NOT NULL,
  motion       VARCHAR(20)   NOT NULL,
  source       VARCHAR(100)  NULL,
  ip_address   VARCHAR(45)   NULL,
  created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_email (email),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
