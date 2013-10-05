CREATE  TABLE `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NULL ,
  `password` VARCHAR(40) NULL ,
  `salt` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) );
