CREATE TABLE `events`
(
    `id`           int(11) unsigned                                              NOT NULL AUTO_INCREMENT,
    `aggregate_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `occurred_on`  datetime                                                      NOT NULL,
    `topic`        varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `payload`      json DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_aggregate-id_topic` (`aggregate_id`, `topic`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;
