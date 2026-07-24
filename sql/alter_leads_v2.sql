-- Migração para bases já criadas com o schema antigo
-- Execute no phpMyAdmin se a tabela leads já existir

USE `bk_autos`;

ALTER TABLE `leads`
  MODIFY `email` VARCHAR(180) NULL;

-- Adiciona modelo_carro se ainda não existir (ignore erro se já existir)
SET @col_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = 'bk_autos'
    AND TABLE_NAME = 'leads'
    AND COLUMN_NAME = 'modelo_carro'
);

SET @sql := IF(
  @col_exists = 0,
  'ALTER TABLE `leads` ADD `modelo_carro` VARCHAR(80) NULL AFTER `telefone`',
  'SELECT 1'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
