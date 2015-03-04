ALTER TABLE semester DROP FOREIGN KEY `fk_semester_id`;

ALTER TABLE semester CHANGE semester_id session_id int(11) NOT NULL;

ALTER TABLE `semester`
ADD CONSTRAINT `fk_session_id` FOREIGN KEY (`session_id`) REFERENCES `session_table` (`id`);
