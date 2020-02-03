CREATE TABLE `correlating_events`
(
    `id`                int(11) unsigned                                              NOT NULL AUTO_INCREMENT,
    `event_id`          char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `correlation_id`    char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `causation_id`      char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `aggregate_id`      char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `aggregate_version` int(11) unsigned                                              NOT NULL,
    `occurred_on`       datetime                                                      NOT NULL,
    `topic`             varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `version`           int(11) unsigned                                              NOT NULL,
    `payload`           json                                                          NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq:event_id` (`event_id`) USING BTREE,
    UNIQUE KEY `uq:aggregate_id:aggregate_version` (`aggregate_id`, `aggregate_version`) USING BTREE,
    KEY `idx:aggregate_id` (`aggregate_id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `simple_events`
(
    `id`                int(11) unsigned                                              NOT NULL AUTO_INCREMENT,
    `event_id`          char(36) COLLATE utf8mb4_general_ci                           NOT NULL,
    `aggregate_id`      char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `aggregate_version` int(11) unsigned                                              NOT NULL,
    `occurred_on`       datetime                                                      NOT NULL,
    `topic`             varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `payload`           json DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq:event_id` (`event_id`) USING BTREE,
    UNIQUE KEY `uq:aggregate_id:aggregate_version` (`aggregate_id`, `aggregate_version`),
    KEY `idx:aggregate_id` (`aggregate_id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;
