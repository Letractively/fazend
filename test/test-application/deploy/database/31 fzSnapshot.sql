--
-- Every object from fzObject may have an unlimited number of
-- snapshots. Every snapshot is a version of the object edited by some
-- particular user (or by anonymous user). Every snapshot has a version,
-- list of PHP-class properties and values (in associative array),
-- and date/time information.
--
-- When you want to change the object, you just create a new snapshot,
-- link it with the object and gives it a version which is bigger than
-- all previous versions.
--

CREATE TABLE IF NOT EXISTS `fzSnapshot` (
    
    `id` INTEGER NOT NULL primary key AUTOINCREMENT,

    -- this snapshot is attached to an object
    -- and saves the latest copy of the data from that object
    `fzObject` INTEGER NOT NULL,
    
    -- every snapshot has information about the data
    -- and the data themselves
    `properties` LONGTEXT BINARY,
    `version` INTEGER NOT NULL DEFAULT 1,
    `alive` BOOLEAN NOT NULL DEFAULT 1,
    `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user` INTEGER,
    `comment` TEXT,
    `baselined` BOOLEAN NOT NULL DEFAULT 0,

    FOREIGN KEY(`fzObject`) REFERENCES `fzObject`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE

    -- unique version number for any particular object
    UNIQUE (`fzObject`, `version`)

);
