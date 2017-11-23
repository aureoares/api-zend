DROP TABLE IF EXISTS `companies`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `members`;

CREATE TABLE `companies` (`companyId` INTEGER PRIMARY KEY AUTOINCREMENT, `name` VARCHAR(255) NOT NULL);
INSERT INTO `companies` (`name`) VALUES ('atSistemas');

CREATE TABLE `teams` (`teamId` INTEGER PRIMARY KEY AUTOINCREMENT, `name` VARCHAR(255) NOT NULL, `companyId` INTEGER NOT NULL);
INSERT INTO `teams` (`name`, `companyId`) VALUES ('PHP', 1);

CREATE TABLE `members` (`memberId` INTEGER PRIMARY KEY AUTOINCREMENT, `name` VARCHAR(255) NOT NULL, `teamId` INTEGER NOT NULL, `joinDate` TIMESTAMP NULL);
INSERT INTO `members` (`name`, `teamId`, `joinDate`) VALUES ('Áureo', 1, '2015-11-22');
