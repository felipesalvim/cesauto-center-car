-- BK Autos — schema de captura de leads + rate limit
-- MySQL 5.7+ / InnoDB / utf8mb4
-- Uso: importar no phpMyAdmin ou: mysql -u root -p < sql/create_leads.sql

CREATE DATABASE IF NOT EXISTS `bk_autos`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `bk_autos`;

-- ---------------------------------------------------------------------------
-- leads: formulário de agendamento / orçamento
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `email` VARCHAR(180) NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `modelo_carro` VARCHAR(80) NULL,
  `servico_interesse` VARCHAR(60) NOT NULL,
  `mensagem` TEXT NULL,
  `lgpd_consent` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `ip` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_leads_created_at` (`created_at`),
  KEY `idx_leads_email` (`email`),
  KEY `idx_leads_servico` (`servico_interesse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- rate_limits: limite por IP + endpoint (ex.: 5 req/min no form)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(45) NOT NULL,
  `endpoint` VARCHAR(64) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rate_lookup` (`ip`, `endpoint`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
