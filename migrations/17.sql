ALTER TABLE semester
ADD UNIQUE INDEX `semester_number_semester_id` (`number`, `session_id`)
