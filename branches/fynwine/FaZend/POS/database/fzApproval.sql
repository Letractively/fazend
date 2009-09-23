--
-- Every snapshot from fzSnapshot table may have a number of requests
-- for approval. Every request means that we expect a given user to approve
-- or reject this particular snapshot.
-- 
-- Every request has date/time information and a link to the user, who
-- is going to give his/her decision.
--

CREATE TABLE IF NOT EXISTS `fzApproval` (
    
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "Unique ID of this approval request",

    -- this snapshot is planned to be approved
    `fzSnapshot` INT UNSIGNED NOT NULL COMMENT "Unique ID of the snapshot, FK to fzSnapshot",
    
    -- when it is created, and what is the status of it
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "When the request is initiated",
    `decision` INT COMMENT "TRUE = approved, FALSE = rejected, NULL = waiting",
    `updated` DATETIME COMMENT "When the decision is made, NULL = waiting",
    `user` INT UNSIGNED NOT NULL COMMENT "Required person for approval",
    `comment` TEXT COMMENT "Optional comment for the request",

    PRIMARY KEY(`id`),
    FOREIGN KEY(`fzSnapshot`) REFERENCES `fzSnapshot`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE

    -- only unique users per snapshot
    UNIQUE(`fzSnapshot`, `user`),

) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ENGINE=InnoDB;

