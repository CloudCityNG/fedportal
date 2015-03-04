ALTER TABLE semester
    DROP KEY `start_date`,
    ADD UNIQUE KEY `start_date1` (`start_date`),
    ADD UNIQUE KEY `end_date` (`end_date`)
