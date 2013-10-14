CREATE  TABLE `torrent` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NULL ,
  `hash` BINARY(20) NOT NULL ,
  `size` BIGINT NOT NULL ,
  `description` TEXT NULL ,
  `hidden` TINYINT(1) NULL DEFAULT 0 ,
  `suspend` TINYINT(1) NOT NULL DEFAULT 0 ,
  `status` TINYINT(1) NULL DEFAULT 0 ,
  `date_created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 ',
  `date_updated` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `hash_UNIQUE` (`hash` ASC) )
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE `torrent_meta` (
  `torrent_id` INT(11) UNSIGNED NOT NULL ,
  `created_by` VARCHAR(100) NULL ,
  `creation_date` TIMESTAMP NULL DEFAULT '0000-00-00' ,
  `comment` TEXT NULL ,
  `total_seeds` INT NULL DEFAULT 0 ,
  `total_leachers` INT NULL DEFAULT 0 ,
  `total_downloaded` INT NULL DEFAULT 0 ,
  `raiting` INT(11) NULL DEFAULT 0 ,
  `total_comments` INT NULL DEFAULT 0 ,
  `last_comment_date` TIMESTAMP NULL ,
  `last_comment_responder` VARCHAR(45) NULL ,
  PRIMARY KEY (`torrent_id`) ,
  INDEX `torrent_meta_fk` (`torrent_id` ASC) ,
  CONSTRAINT `torrent_meta_fk`
    FOREIGN KEY (`torrent_id` )
    REFERENCES `torrent` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
