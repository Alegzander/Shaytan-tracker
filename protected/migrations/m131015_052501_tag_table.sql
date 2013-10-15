CREATE  TABLE `tracker`.`tag` (
  `id` INT(11) UNSIGNED NOT NULL ,
  `tag` VARCHAR(50) NOT NULL ,
  `weight` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `tag_UNIQUE` (`tag` ASC) )
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;