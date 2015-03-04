ALTER TABLE semester DROP KEY `number`;
ALTER TABLE semester ADD UNIQUE KEY `number_session_id` (number, session_id);
