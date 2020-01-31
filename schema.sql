CREATE TABLE `simple_events`
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

CREATE TABLE `correlating_events`
(
    `id`             int(11) unsigned                                              NOT NULL AUTO_INCREMENT,
    `event_id`       char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `correlation_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `causation_id`   char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `aggregate_id`   char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `occurred_on`    datetime                                                      NOT NULL,
    `topic`          varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `version`        int(11) unsigned                                              NOT NULL,
    `payload`        json                                                          NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_event-id` (`aggregate_id`),
    KEY `idx_aggregate-id_topic` (`aggregate_id`, `topic`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;
