CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `aggregate_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `occurred_on` datetime NOT NULL,
  `event_type` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `payload` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `event_id_UNIQUE` (`event_id`),
  ADD KEY `aggregate_id_event_class` (`aggregate_id`,`event_type`);

ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
