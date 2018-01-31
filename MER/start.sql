-- MySQL Workbench Synchronization
-- Generated: 2018-01-31 09:01
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: jonatas

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS quake_log_parser DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS quake_log_parser.game (
  id_game INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_game))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS quake_log_parser.players (
  id_players INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  id_game INT(11) NOT NULL,
  PRIMARY KEY (id_players),
  INDEX fk_players_game_idx (id_game ASC),
  CONSTRAINT fk_players_game
    FOREIGN KEY (id_game)
    REFERENCES quake_log_parser.game (id_game)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS quake_log_parser.kills_by_means (
  id_kills_by_means INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  id_game INT(11) NOT NULL,
  PRIMARY KEY (id_kills_by_means),
  INDEX fk_kills_by_means_game_idx (id_game ASC),
  CONSTRAINT fk_kills_by_means_game
    FOREIGN KEY (id_game)
    REFERENCES quake_log_parser.game (id_game)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS quake_log_parser.kills (
  id_kills INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  id_players INT(11) NOT NULL,
  total VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_kills),
  INDEX fk_kills_players_idx (id_players ASC),
  CONSTRAINT fk_kills_players
    FOREIGN KEY (id_players)
    REFERENCES quake_log_parser.players (id_players)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS quake_log_parser.dados (
  id_dados INT(11) NOT NULL AUTO_INCREMENT,
  died VARCHAR(45) NULL DEFAULT NULL,
  causes_death VARCHAR(45) NULL DEFAULT NULL,
  killed VARCHAR(45) NULL DEFAULT NULL,
  text VARCHAR(45) NULL DEFAULT NULL,
  id_kills INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (id_dados),
  INDEX fk_dados_kills_idx (id_kills ASC),
  CONSTRAINT fk_dados_kills
    FOREIGN KEY (id_kills)
    REFERENCES quake_log_parser.kills (id_kills)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
