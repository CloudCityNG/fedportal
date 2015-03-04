ALTER TABLE `session_table`
ADD UNIQUE `session` (`session`),
ADD UNIQUE `start_date_end_date` (`start_date`, `end_date`)