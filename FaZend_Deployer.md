# Usage sample #

You create a file `application/deploy/database/1 user.sql`:

```
--
-- This is a simple table for the list of users
--
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "Unique ID of the user",
  `email` VARBINARY(200) NOT NULL COMMENT "Unique user email",
  `password` VARBINARY(50) NOT NULL COMMENT "User password",
  PRIMARY KEY USING BTREE (`id`),
  UNIQUE (`email`)
  ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ENGINE=InnoDB;
```

That's it. The database will be deployed automatically during the next continuous integration lifecycle.

Files shall be named as `<number> <space> <table name> ".sql"`, where `<number>` is an ordering number, to let Deployer know which table shall be created first, which second, and so forth.