CREATE TABLE `events`
(
    `id`                   int(11) unsigned                                              NOT NULL AUTO_INCREMENT,
    `event_id`             char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `correlation_id`       char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `causation_id`         char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `event_stream_id`      char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL,
    `event_stream_version` int(11) unsigned                                              NOT NULL,
    `occurred_on`          datetime                                                      NOT NULL,
    `topic`                varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `version`              int(11) unsigned                                              NOT NULL,
    `payload`              json                                                          NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq:event_id` (`event_id`) USING BTREE,
    UNIQUE KEY `uq:event_stream_id:event_stream_version` (`event_stream_id`, `event_stream_version`) USING BTREE,
    KEY `idx:event_stream_id` (`event_stream_id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;
