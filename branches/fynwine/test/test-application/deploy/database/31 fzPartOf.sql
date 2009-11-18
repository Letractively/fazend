--
-- Rows in fzPartOf are at the same time instances of
-- class fzObject. That's why fzObject column is a primary key
-- and at the same time is a FK to fzObject table. When you want to
-- create a row in fzPartOf you should first create a row in fzObject.
-- And then you should link fzPartOf and fzObject.
--
-- That will give the ability to work with links between objects
-- the same way as you work with objects (trace versions, approve, baseline, etc.)
--

CREATE TABLE IF NOT EXISTS `fzPartOf` (
    
    `name` VARBINARY(255) NOT NULL,
    `fzObject` INTEGER NOT NULL,
    `parent` INTEGER NOT NULL,
    `kid` INTEGER NOT NULL,

    PRIMARY KEY(`fzObject`),
    FOREIGN KEY(`fzObject`) REFERENCES `fzObject`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(`parent`) REFERENCES `fzObject`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(`kid`) REFERENCES `fzObject`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,

    -- relation name is unique for any particular object
    UNIQUE(`parent`, `name`)

);
