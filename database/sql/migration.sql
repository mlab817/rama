-- rama.jobs definition

CREATE TABLE `jobs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
`attempts` tinyint unsigned NOT NULL,
`reserved_at` int unsigned DEFAULT NULL,
`available_at` int unsigned NOT NULL,
`created_at` int unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.notifications definition

CREATE TABLE `notifications` (
 `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
 `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `notifiable_id` bigint unsigned NOT NULL,
 `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
 `read_at` timestamp NULL DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.operators definition

CREATE TABLE `operators` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `region_id` int unsigned DEFAULT NULL,
 `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `full_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `operators_region_id_foreign` (`region_id`),
 CONSTRAINT `operators_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.operator_route definition

CREATE TABLE `operator_route` (
  `operator_id` bigint unsigned NOT NULL,
  `route_id` bigint unsigned NOT NULL,
  KEY `operator_route_operator_id_foreign` (`operator_id`),
  CONSTRAINT `operator_route_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.trips definition

CREATE TABLE `trips` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `plate_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `start_date` date NOT NULL,
 `start_time` time NOT NULL,
 `end_date` date DEFAULT NULL,
 `end_time` time DEFAULT NULL,
 `station_id` int unsigned DEFAULT NULL,
 `bound` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `is_validated` tinyint DEFAULT '0',
 `user_id` bigint unsigned DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `trips_station_id_foreign` (`station_id`),
 CONSTRAINT `trips_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.weekly_report_batches definition

CREATE TABLE `weekly_report_batches` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `week_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `start_date` date NOT NULL,
 `end_date` date NOT NULL,
 `user_id` int unsigned DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `weekly_report_batches_user_id_foreign` (`user_id`),
 CONSTRAINT `weekly_report_batches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- rama.weekly_reports definition

CREATE TABLE `weekly_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `weekly_report_batch_id` bigint unsigned NOT NULL,
  `operator_id` bigint unsigned NOT NULL,
  `filepath` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `weekly_reports_weekly_report_batch_id_foreign` (`weekly_report_batch_id`),
  KEY `weekly_reports_operator_id_foreign` (`operator_id`),
  CONSTRAINT `weekly_reports_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE CASCADE,
  CONSTRAINT `weekly_reports_weekly_report_batch_id_foreign` FOREIGN KEY (`weekly_report_batch_id`) REFERENCES `weekly_report_batches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
