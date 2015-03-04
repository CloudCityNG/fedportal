CREATE TABLE IF NOT EXISTS `academic_departments` (
  `code` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL,
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `academic_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


CREATE TABLE IF NOT EXISTS `academic_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_year` int(10) unsigned NOT NULL,
  `end_year` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `start_year` (`start_year`,`end_year`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `admin_fin_table` (
  `pass` varchar(20) NOT NULL,
  PRIMARY KEY (`pass`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `admin_table` (
  `pass` varchar(20) NOT NULL,
  PRIMARY KEY (`pass`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `course_reg` (
  `session` varchar(20) NOT NULL,
  `personalno` varchar(20) NOT NULL,
  `class` varchar(20) NOT NULL,
  `courses` text NOT NULL,
  `department` varchar(20) NOT NULL,
  `units` text NOT NULL,
  `codes` text NOT NULL,
  `totalunits` tinyint(4) NOT NULL,
  UNIQUE KEY `session` (`session`,`personalno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `course_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `unit` int(10) unsigned NOT NULL,
  `department` varchar(20) NOT NULL,
  `semester` int(11) NOT NULL,
  `class` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_table_code_department_semester` (`code`,`department`,`semester`,`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `edu_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `pry_edu` text COLLATE utf8_unicode_ci NOT NULL,
  `secondary_edu` text COLLATE utf8_unicode_ci NOT NULL,
  `o_level_scores` text COLLATE utf8_unicode_ci NOT NULL,
  `post_secondary` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `freshman_olevels` (
  `personalno` varchar(50) NOT NULL DEFAULT '',
  `nameofexam1` varchar(40) NOT NULL DEFAULT '',
  `examno1` varchar(40) NOT NULL DEFAULT '',
  `nameofexam2` varchar(40) DEFAULT NULL,
  `examno2` varchar(40) DEFAULT NULL,
  `institution1` varchar(50) NOT NULL DEFAULT '',
  `institution2` varchar(50) DEFAULT NULL,
  `duration1` varchar(50) NOT NULL DEFAULT '',
  `duration2` varchar(50) DEFAULT NULL,
  `certificate1` varchar(50) NOT NULL DEFAULT '',
  `certificate2` varchar(50) DEFAULT NULL,
  `grades1` text NOT NULL,
  `grades2` text NOT NULL,
  PRIMARY KEY (`personalno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `freshman_profile` (
  `first_name` varchar(200) DEFAULT NULL,
  `personalno` varchar(40) NOT NULL DEFAULT '',
  `surname` varchar(50) NOT NULL DEFAULT '',
  `other_names` varchar(200) DEFAULT NULL,
  `previousname` varchar(50) DEFAULT NULL,
  `sex` varchar(20) NOT NULL DEFAULT '',
  `dateofbirth` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `permanentaddress` varchar(50) NOT NULL DEFAULT '',
  `nationality` varchar(30) DEFAULT NULL,
  `state` varchar(30) NOT NULL DEFAULT '',
  `lga` varchar(40) NOT NULL DEFAULT '',
  `course` varchar(40) NOT NULL DEFAULT '',
  `parentname` varchar(50) DEFAULT NULL,
  `contactperson` varchar(100) DEFAULT NULL,
  `activities` varchar(100) DEFAULT NULL,
  `currentsession` varchar(10) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`personalno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `medical_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `blood_group` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `genotype` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `allergies` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `medical_conditions` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_name` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_mobile_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_address` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `pics` (
  `personalno` varchar(40) NOT NULL DEFAULT '',
  `nameofpic` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`personalno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pin_table` (
  `number` varchar(20) NOT NULL,
  `pass` varchar(30) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pin_table_new` (
  `number` varchar(20) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `school_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `academic_year` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `academic_level` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `fee` decimal(13,2) NOT NULL,
  `department` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_fees_academic_level_academic_year_department` (`academic_level`,`academic_year`,`department`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `semester` (
  `number` int(10) unsigned NOT NULL,
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `session_table` (
  `session` varchar(20) NOT NULL,
  PRIMARY KEY (`session`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `student_billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `academic_year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `level` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `department_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`,`academic_year`,`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `student_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `academic_year_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `reg_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `semester` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `level` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no_academic_year_code_semester_course_id` (`reg_no`,`academic_year_code`,`semester`,`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `student_currents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `academic_year` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `level` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `dept_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dept_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_currents_reg_no_academic_year` (`reg_no`,`academic_year`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `student_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `academic_year` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `dept_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `remark` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `receipt_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

