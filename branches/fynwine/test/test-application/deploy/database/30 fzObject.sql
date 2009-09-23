--
-- All objects are saved in fzObject, without properties, versions
-- or any other attributes. Here we just keep the ID and the CLASS.
--
-- Every object may have a number of SNAPSHOTS, stored as rows in 
-- fzSnapshot table.
--
--

CREATE TABLE IF NOT EXISTS `fzObject` (

    `id` INTEGER NOT NULL primary key AUTOINCREMENT,
    `class` VARBINARY(255) NOT NULL
);

