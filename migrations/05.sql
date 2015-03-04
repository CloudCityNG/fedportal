ALTER TABLE `semester`
ADD `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
ADD `start_date` date NOT NULL,
ADD `end_date` date NOT NULL,
ADD `created_at` timestamp NULL,
ADD `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
ADD `current` varchar(10) NULL DEFAULT NULL;
