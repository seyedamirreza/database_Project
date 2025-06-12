CREATE TABLE `users` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `firstName` varchar(255) NOT NULL,
                         `lastName` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `phoneNumber` varchar(255) NOT NULL,
                         `role` varchar(255) NOT NULL,
                         `registerDate` date NOT NULL,
                         `city` varchar(255) NOT NULL,
                         `email` varchar(255) DEFAULT NULL,
                         `accountState` tinyint(1) NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL
)




CREATE TABLE `companies` (
                             `id` bigint(20) UNSIGNED NOT NULL,
                             `name` varchar(255) NOT NULL
)

CREATE TABLE `type_vehicle` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `name` varchar(255) NOT NULL
)
CREATE TABLE `vehicles` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `name` varchar(255) NOT NULL,
                            `company_id` bigint(20) UNSIGNED NOT NULL,
                            `facilities` varchar(255) NOT NULL,
                            `type_vehicle_id` bigint(20) UNSIGNED NOT NULL
)

CREATE TABLE `class_airs` (
                              `id` bigint(20) UNSIGNED NOT NULL,
                              `name` varchar(255) NOT NULL
)
CREATE TABLE `flights` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `vehicle_id` bigint(20) UNSIGNED NOT NULL,
                           `class_air_id` bigint(20) UNSIGNED NOT NULL,
                           `airline_name` varchar(255) NOT NULL,
                           `flight_number` varchar(255) NOT NULL,
                           `source_airport` varchar(255) NOT NULL,
                           `destination_airport` varchar(255) NOT NULL,
                           `stops` int(11) NOT NULL
)
CREATE TABLE `class_trains` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL
)
CREATE TABLE `trains` (
                          `id` bigint(20) UNSIGNED NOT NULL,
                          `vehicle_id` bigint(20) UNSIGNED NOT NULL,
                          `class_train_id` bigint(20) UNSIGNED NOT NULL,
                          `component` text NOT NULL
)
CREATE TABLE `class_buses` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `name` varchar(255) NOT NULL
)
CREATE TABLE `buses` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `vehicle_id` bigint(20) UNSIGNED NOT NULL,
                         `class_bus_id` bigint(20) UNSIGNED NOT NULL,
                         `company_name` varchar(255) NOT NULL,
                         `capacity` int(11) NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL
)
CREATE TABLE `tickets` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `vehicle_id` bigint(20) UNSIGNED NOT NULL,
                           `price` int(11) NOT NULL,
                           `remaining_cap` int(11) NOT NULL,
                           `is_two_way` tinyint(1) NOT NULL,
                           `source` varchar(255) NOT NULL,
                           `destination` varchar(255) NOT NULL,
                           `arrival_time` date NOT NULL,
                           `departure_time` date NOT NULL,
                           `class` varchar(255) NOT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL
)
CREATE TABLE `reservations` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                `user_id` bigint(20) UNSIGNED NOT NULL,
                                `status` tinyint(1) NOT NULL,
                                `reserve_time` datetime NOT NULL,
                                `expire_time` datetime NOT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL
)
CREATE TABLE `payments` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `reservation_id` bigint(20) UNSIGNED NOT NULL,
                            `status` tinyint(1) NOT NULL,
                            `payment_method` varchar(255) NOT NULL,
                            `time` time NOT NULL
)
CREATE TABLE `reports` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `reservation_id` bigint(20) UNSIGNED NOT NULL,
                           `title` varchar(255) NOT NULL,
                           `status` tinyint(1) NOT NULL,
                           `body` text NOT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL
)
CREATE TABLE `wallets` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `user_id` bigint(20) UNSIGNED NOT NULL,
                           `value` int(11) NOT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL
)
