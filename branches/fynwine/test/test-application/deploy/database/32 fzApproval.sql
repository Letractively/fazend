--
-- Every snapshot from fzSnapshot table may have a number of requests
-- for approval. Every request means that we expect a given user to approve
-- or reject this particular snapshot.
-- 
-- Every request has date/time information and a link to the user, who
-- is going to give his/her decision.
--

CREATE TABLE IF NOT EXISTS `fzApproval` (
    
    `id` INTEGER NOT NULL primary key AUTOINCREMENT,

    -- this snapshot is planned to be approved
    `fzSnapshot` INT UNSIGNED NOT NULL,
    
    -- when it is created, and what is the status of it
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `decision` INT,
    `updated` DATETIME,
    `user` INT UNSIGNED NOT NULL,
    `comment` TEXT COMMENT,

    FOREIGN KEY(`fzSnapshot`) REFERENCES `fzSnapshot`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE

    -- only unique users per snapshot
    UNIQUE(`fzSnapshot`, `user`)

);

