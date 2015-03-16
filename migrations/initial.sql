CREATE TABLE IF NOT EXISTS `academic_departments` (
  `code`        VARCHAR(20)  NOT NULL,
  `description` VARCHAR(200) NOT NULL,
  UNIQUE KEY `code` (`code`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `academic_levels` (
  `id`          INT(11)                 NOT NULL AUTO_INCREMENT,
  `code`        VARCHAR(5)
                COLLATE utf8_unicode_ci NOT NULL,
  `description` VARCHAR(255)
                COLLATE utf8_unicode_ci          DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `academic_sessions` (
  `id`         INT(11)          NOT NULL        AUTO_INCREMENT,
  `code`       VARCHAR(9)
               COLLATE utf8_unicode_ci          DEFAULT NULL,
  `start_year` INT(10) UNSIGNED NOT NULL,
  `end_year`   INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `start_year` (`start_year`, `end_year`),
  UNIQUE KEY `code` (`code`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `admin_fin_table` (
  `pass` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`pass`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `admin_table` (
  `pass` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`pass`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `course_reg` (
  `session`    VARCHAR(20) NOT NULL,
  `personalno` VARCHAR(20) NOT NULL,
  `class`      VARCHAR(20) NOT NULL,
  `courses`    TEXT        NOT NULL,
  `department` VARCHAR(20) NOT NULL,
  `units`      TEXT        NOT NULL,
  `codes`      TEXT        NOT NULL,
  `totalunits` TINYINT(4)  NOT NULL,
  UNIQUE KEY `session` (`session`, `personalno`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `course_table` (
  `id`         INT(11)          NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(50)      NOT NULL,
  `code`       VARCHAR(20)      NOT NULL,
  `unit`       INT(10) UNSIGNED NOT NULL,
  `department` VARCHAR(20)      NOT NULL,
  `semester`   INT(11)          NOT NULL,
  `class`      VARCHAR(4)       NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_table_code_department_semester` (`code`, `department`, `semester`, `class`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `edu_history` (
  `id`             INT(11)                 NOT NULL AUTO_INCREMENT,
  `reg_no`         VARCHAR(25)
                   COLLATE utf8_unicode_ci NOT NULL,
  `pry_edu`        TEXT
                   COLLATE utf8_unicode_ci NOT NULL,
  `secondary_edu`  TEXT
                   COLLATE utf8_unicode_ci NOT NULL,
  `o_level_scores` TEXT
                   COLLATE utf8_unicode_ci NOT NULL,
  `post_secondary` TEXT
                   COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `freshman_olevels` (
  `personalno`   VARCHAR(50) NOT NULL DEFAULT '',
  `nameofexam1`  VARCHAR(40) NOT NULL DEFAULT '',
  `examno1`      VARCHAR(40) NOT NULL DEFAULT '',
  `nameofexam2`  VARCHAR(40)          DEFAULT NULL,
  `examno2`      VARCHAR(40)          DEFAULT NULL,
  `institution1` VARCHAR(50) NOT NULL DEFAULT '',
  `institution2` VARCHAR(50)          DEFAULT NULL,
  `duration1`    VARCHAR(50) NOT NULL DEFAULT '',
  `duration2`    VARCHAR(50)          DEFAULT NULL,
  `certificate1` VARCHAR(50) NOT NULL DEFAULT '',
  `certificate2` VARCHAR(50)          DEFAULT NULL,
  `grades1`      TEXT        NOT NULL,
  `grades2`      TEXT        NOT NULL,
  PRIMARY KEY (`personalno`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `freshman_profile` (
  `first_name`       VARCHAR(200)         DEFAULT NULL,
  `personalno`       VARCHAR(40) NOT NULL DEFAULT '',
  `surname`          VARCHAR(50) NOT NULL DEFAULT '',
  `other_names`      VARCHAR(200)         DEFAULT NULL,
  `previousname`     VARCHAR(50)          DEFAULT NULL,
  `sex`              VARCHAR(20) NOT NULL DEFAULT '',
  `dateofbirth`      VARCHAR(30) NOT NULL DEFAULT '',
  `email`            VARCHAR(30) NOT NULL DEFAULT '',
  `phone`            VARCHAR(30) NOT NULL DEFAULT '',
  `permanentaddress` VARCHAR(50) NOT NULL DEFAULT '',
  `nationality`      VARCHAR(30)          DEFAULT NULL,
  `state`            VARCHAR(30) NOT NULL DEFAULT '',
  `lga`              VARCHAR(40) NOT NULL DEFAULT '',
  `course`           VARCHAR(40) NOT NULL DEFAULT '',
  `parentname`       VARCHAR(50)          DEFAULT NULL,
  `contactperson`    VARCHAR(100)         DEFAULT NULL,
  `activities`       VARCHAR(100)         DEFAULT NULL,
  `currentsession`   VARCHAR(10) NOT NULL DEFAULT '',
  `created_at`       TIMESTAMP   NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at`       TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at`       TIMESTAMP   NULL     DEFAULT NULL,
  PRIMARY KEY (`personalno`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `medical_info` (
  `id`                  INT(11)                 NOT NULL AUTO_INCREMENT,
  `reg_no`              VARCHAR(20)
                        COLLATE utf8_unicode_ci NOT NULL,
  `blood_group`         VARCHAR(5)
                        COLLATE utf8_unicode_ci NOT NULL,
  `genotype`            VARCHAR(5)
                        COLLATE utf8_unicode_ci NOT NULL,
  `allergies`           VARCHAR(300)
                        COLLATE utf8_unicode_ci          DEFAULT NULL,
  `medical_conditions`  VARCHAR(300)
                        COLLATE utf8_unicode_ci          DEFAULT NULL,
  `doctor_name`         VARCHAR(300)
                        COLLATE utf8_unicode_ci          DEFAULT NULL,
  `doctor_mobile_phone` VARCHAR(20)
                        COLLATE utf8_unicode_ci          DEFAULT NULL,
  `doctor_address`      VARCHAR(300)
                        COLLATE utf8_unicode_ci          DEFAULT NULL,
  `created_at`          TIMESTAMP               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at`          TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `pics` (
  `personalno` VARCHAR(40)  NOT NULL DEFAULT '',
  `nameofpic`  VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`personalno`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `pin_table` (
  `number`     VARCHAR(20) NOT NULL,
  `pass`       VARCHAR(30)          DEFAULT NULL,
  `email`      VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`number`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `pin_table_new` (
  `number` VARCHAR(20) NOT NULL,
  `pass`   VARCHAR(30) NOT NULL,
  `email`  VARCHAR(50) NOT NULL,
  PRIMARY KEY (`number`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `school_fees` (
  `id`             INT(11)                 NOT NULL AUTO_INCREMENT,
  `academic_year`  VARCHAR(9)
                   COLLATE utf8_unicode_ci NOT NULL,
  `academic_level` VARCHAR(4)
                   COLLATE utf8_unicode_ci NOT NULL,
  `fee`            DECIMAL(13, 2)          NOT NULL,
  `department`     VARCHAR(20)
                   COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_fees_academic_level_academic_year_department` (`academic_level`, `academic_year`, `department`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `semester` (
  `number` INT(10) UNSIGNED NOT NULL,
  UNIQUE KEY `number` (`number`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `session_table` (
  `session` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`session`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


CREATE TABLE IF NOT EXISTS `student_billing` (
  `id`              INT(11)                 NOT NULL AUTO_INCREMENT,
  `reg_no`          VARCHAR(100)
                    COLLATE utf8_unicode_ci NOT NULL,
  `academic_year`   VARCHAR(10)
                    COLLATE utf8_unicode_ci NOT NULL,
  `level`           VARCHAR(4)
                    COLLATE utf8_unicode_ci NOT NULL,
  `department_code` VARCHAR(20)
                    COLLATE utf8_unicode_ci NOT NULL,
  `amount`          DECIMAL(13, 2)                   DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`, `academic_year`, `level`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `student_courses` (
  `id`                 INT(11)                 NOT NULL AUTO_INCREMENT,
  `academic_year_code` VARCHAR(10)
                       COLLATE utf8_unicode_ci NOT NULL,
  `reg_no`             VARCHAR(20)
                       COLLATE utf8_unicode_ci NOT NULL,
  `semester`           INT(11)                 NOT NULL,
  `course_id`          INT(11)                 NOT NULL,
  `level`              VARCHAR(4)
                       COLLATE utf8_unicode_ci          DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no_academic_year_code_semester_course_id` (`reg_no`, `academic_year_code`, `semester`, `course_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `student_currents` (
  `id`            INT(11)                 NOT NULL AUTO_INCREMENT,
  `reg_no`        VARCHAR(20)
                  COLLATE utf8_unicode_ci NOT NULL,
  `academic_year` VARCHAR(9)
                  COLLATE utf8_unicode_ci NOT NULL,
  `level`         VARCHAR(4)
                  COLLATE utf8_unicode_ci NOT NULL,
  `dept_code`     VARCHAR(30)
                  COLLATE utf8_unicode_ci NOT NULL,
  `dept_name`     VARCHAR(150)
                  COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_currents_reg_no_academic_year` (`reg_no`, `academic_year`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `student_payment` (
  `id`            INT(11)                 NOT NULL AUTO_INCREMENT,
  `reg_no`        VARCHAR(30)
                  COLLATE utf8_unicode_ci NOT NULL,
  `academic_year` VARCHAR(9)
                  COLLATE utf8_unicode_ci          DEFAULT NULL,
  `level`         VARCHAR(4)
                  COLLATE utf8_unicode_ci NOT NULL,
  `dept_code`     VARCHAR(30)
                  COLLATE utf8_unicode_ci NOT NULL,
  `amount`        DECIMAL(15, 2)          NOT NULL,
  `remark`        VARCHAR(300)
                  COLLATE utf8_unicode_ci NOT NULL,
  `receipt_no`    VARCHAR(30)
                  COLLATE utf8_unicode_ci NOT NULL,
  `created_at`    DATETIME                NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

ALTER TABLE `session_table` CHANGE `session` `session` VARCHAR(9) NOT NULL;

ALTER TABLE `session_table` DROP PRIMARY KEY;

ALTER TABLE `session_table` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
FIRST;

ALTER TABLE `session_table`
ADD `start_date` DATE NOT NULL,
ADD `end_date` DATE NOT NULL,
ADD `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
ADD `current` VARCHAR(10) NULL DEFAULT NULL;

ALTER TABLE `semester`
ADD `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
FIRST,
ADD `start_date` DATE NOT NULL,
ADD `end_date` DATE NOT NULL,
ADD `created_at` TIMESTAMP NULL,
ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
ADD `current` VARCHAR(10) NULL DEFAULT NULL;

ALTER TABLE `semester` CHANGE `created_at`
`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `semester` ADD UNIQUE (`start_date`, `end_date`);

ALTER TABLE `semester` DROP `current`;

ALTER TABLE `semester` ADD `semester_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `semester` ADD CONSTRAINT `fk_semester_id` FOREIGN KEY (`semester_id`) REFERENCES `session_table` (`id`);

ALTER TABLE `session_table` DROP `current`;

ALTER TABLE `session_table`
ADD UNIQUE `session` (`session`),
ADD UNIQUE `start_date_end_date` (`start_date`, `end_date`);

ALTER TABLE semester DROP FOREIGN KEY `fk_semester_id`;

ALTER TABLE semester CHANGE semester_id session_id INT(11) NOT NULL;

ALTER TABLE `semester`
ADD CONSTRAINT `fk_session_id` FOREIGN KEY (`session_id`) REFERENCES `session_table` (`id`);

ALTER TABLE semester DROP KEY `number`;
ALTER TABLE semester ADD UNIQUE KEY `number_session_id` (number, session_id);

ALTER TABLE semester
DROP KEY `start_date`,
ADD UNIQUE KEY `start_date1` (`start_date`),
ADD UNIQUE KEY `end_date` (`end_date`);

ALTER TABLE course_table CHANGE `title` `title` VARCHAR(300) NOT NULL;

ALTER TABLE semester
ADD UNIQUE INDEX `semester_number_semester_id` (`number`, `session_id`);

ALTER TABLE semester DROP INDEX `number_session_id`;


INSERT INTO `academic_departments` (`code`, `description`) VALUES
  ('dental_technology', 'DENTAL TECHNOLOGY'),
  ('dental_therapy', 'DENTAL THERAPY');

INSERT INTO `academic_levels` (`id`, `code`, `description`) VALUES
  (11, 'OND1', NULL),
  (12, 'OND2', NULL),
  (13, 'HND1', NULL),
  (14, 'HND2', NULL);


INSERT INTO `academic_sessions` (`id`, `code`, `start_year`, `end_year`) VALUES
  (1, '2009/2010', 2009, 2010),
  (2, '2010/2011', 2010, 2011),
  (3, '2011/2012', 2011, 2012),
  (4, '2012/2013', 2012, 2013),
  (5, '2013/2014', 2013, 2014),
  (6, '2014/2015', 2014, 2015);


INSERT INTO `admin_fin_table` (`pass`) VALUES
  ('finance');


INSERT INTO `admin_table` (`pass`) VALUES
  ('academics');


INSERT INTO `course_table` (`id`, `title`, `code`, `unit`, `department`, `semester`, `class`) VALUES
  (242, 'Logic and Linear Algebra', 'MTH.111', 2, 'dental_technology', 1, 'OND1'),
  (243, 'General and Physical Chemistry', 'BCH.111', 2, 'dental_technology', 1, 'OND1'),
  (244, 'Mechanics and Properties of Mater', 'BPH.111', 2, 'dental_technology', 1, 'OND1'),
  (245, 'Biology', 'BBA.111', 2, 'dental_technology', 1, 'OND1'),
  (246, 'Introduction to Statistics', 'STA.111', 2, 'dental_technology', 1, 'OND1'),
  (247, 'Communication in English', 'GNS.101', 2, 'dental_technology', 1, 'OND1'),
  (248, 'Introduction to Dental Technology', 'DTE.111', 2, 'dental_technology', 1, 'OND1'),
  (249, 'Primary Health Care (PHC)', 'DTH.112', 2, 'dental_technology', 1, 'OND1'),
  (250, 'Science and Properties of Materials', 'CEC.104', 2, 'dental_technology', 1, 'OND1'),
  (251, 'Morphology and Physiology of Living Things', 'STB.111', 2, 'dental_technology', 1, 'OND1'),
  (252, 'Introduction to Dental Technology (Practical)', 'DTE.111P', 2, 'dental_technology', 1, 'OND1'),
  (253, 'Algebra and Elementary Trigonometry', 'MTH.121', 2, 'dental_technology', 2, 'OND1'),
  (254, 'General Laboratory Technique I', 'GLT.121', 2, 'dental_technology', 2, 'OND1'),
  (255, 'Organic and Inorganic chemistry', 'BCH.121', 2, 'dental_technology', 2, 'OND1'),
  (256, 'Physics (Optics, Wave, Electricity & Magnetism)', 'BPH.121', 2, 'dental_technology', 2, 'OND1'),
  (257, 'Technical Drawing', 'PTD.111', 3, 'dental_technology', 2, 'OND1'),
  (258, 'Communication in English', 'GNS.102', 2, 'dental_technology', 2, 'OND1'),
  (259, 'Medical Sociology', 'GNS.124', 2, 'dental_technology', 2, 'OND1'),
  (260, 'Biology', 'BBA.121', 2, 'dental_technology', 2, 'OND1'),
  (261, 'Introduction to Dental Prosthetic & Technology', 'DTE.121', 2, 'dental_technology', 2, 'OND1'),
  (262, 'Introduction to Dental Prosthetic & Technology', 'DTE.121P', 2, 'dental_technology', 2, 'OND1'),
  (263, 'General Laboratory Technology', 'GLT.211', 2, 'dental_technology', 1, 'OND2'),
  (264, 'Introductory Microbiology', 'STC.211', 2, 'dental_technology', 1, 'OND2'),
  (265, 'Human Physiology', 'NUD.231', 2, 'dental_technology', 1, 'OND2'),
  (266, 'Human Anatomy and Physiology', 'DTH.115', 2, 'dental_technology', 1, 'OND2'),
  (267, 'Oral Anatomy and Physiology I', 'DTH.211', 2, 'dental_technology', 1, 'OND2'),
  (268, 'Dental Laboratory Engineering Techniques', 'DTE.213', 2, 'dental_technology', 1, 'OND2'),
  (269, 'Introduction to Dental Materials', 'DTE.214', 2, 'dental_technology', 1, 'OND2'),
  (270, 'Dental Radiography I', 'DTH.217', 2, 'dental_technology', 1, 'OND2'),
  (271, 'Partial Denture Prosthetics I', 'DTE.225', 2, 'dental_technology', 1, 'OND2'),
  (272, 'Communication in English', 'GNS.201', 2, 'dental_technology', 1, 'OND2'),
  (273, 'Citizenship Education', 'GNS.121', 2, 'dental_technology', 1, 'OND2'),
  (274, 'Partial Denture Prosthetics I (Practical)', 'DTE.225P', 2, 'dental_technology', 1, 'OND2'),
  (275, 'Biostatistics', 'STA.225', 2, 'dental_technology', 2, 'OND2'),
  (276, 'Science of Dental Technology', 'DTE.221', 2, 'dental_technology', 2, 'OND2'),
  (277, 'Complete Denture Prosthetics I', 'DTE.222', 2, 'dental_technology', 2, 'OND2'),
  (278, 'Science of Dental Material I', 'DTE.223', 2, 'dental_technology', 2, 'OND2'),
  (279, 'Dental Prosthetics and Technique', 'DTE.224', 2, 'dental_technology', 2, 'OND2'),
  (280, 'Oral Pathology and Microbiological', 'DTE.226', 2, 'dental_technology', 2, 'OND2'),
  (281, 'Seminar', 'DTE.227', 4, 'dental_technology', 2, 'OND2'),
  (282, 'Oral Physiology, Embryology & Histology', 'DTE.212', 2, 'dental_technology', 2, 'OND2'),
  (283, 'Use of English', 'GNS.202', 2, 'dental_technology', 2, 'OND2'),
  (284, 'Complete Denture Prosthetics (Practical)', 'DTE.222P', 2, 'dental_technology', 2, 'OND2'),
  (285, 'Dental Prosthetics & Techniques (Practical)', 'DTE.224P', 2, 'dental_technology', 2, 'OND2'),
  (297, 'Science of Dental Materials', 'DTE.311', 2, 'dental_technology', 1, 'HND1'),
  (298, 'Complete Denture Prosthetics I', 'DTE.314', 2, 'dental_technology', 1, 'HND1'),
  (299, 'Dental Prosthetics Technology I', 'DTE.315', 2, 'dental_technology', 1, 'HND1'),
  (300, 'Functional Anatomy of Mastication Swallowing & Spe', 'DTE.316', 2, 'dental_technology', 1, 'HND1'),
  (301, 'Principles of Dental Repair, Relining Rebasing & A', 'DTE.317', 2, 'dental_technology', 1, 'HND1'),
  (302, 'Dental Conservation Prosthetics I', 'DTE.312', 2, 'dental_technology', 1, 'HND1'),
  (303, 'Metallurgy in Dental technology', 'DTE.313', 2, 'dental_technology', 1, 'HND1'),
  (304, 'Communication in English', 'GNS.301', 2, 'dental_technology', 1, 'HND1'),
  (305, 'Basic Computer', 'COM.301', 2, 'dental_technology', 1, 'HND1'),
  (306, 'Instrumentation (General)', 'GLT.302', 2, 'dental_technology', 1, 'HND1'),
  (307, 'Complete Denture Prosthetics I (Practical)', 'DTE.314P', 2, 'dental_technology', 1, 'HND1'),
  (308, 'Dental Prosthetics Technology I (Practical)', 'DTE.315P', 2, 'dental_technology', 1, 'HND1'),
  (309, 'Dental Conservation Prosthetics I (Practical)', 'DTE.312P', 2, 'dental_technology', 1, 'HND1'),
  (310, 'Principles of Dental Repairs, Relining Rebasing an', 'DTE.317P', 2, 'dental_technology', 1, 'HND1'),
  (325, 'Polymer Chemistry', 'PLT.211', 2, 'dental_technology', 2, 'HND1'),
  (326, 'Bacteriology', 'STM.311', 2, 'dental_technology', 2, 'HND1'),
  (327, 'Computer Basic and Programme', 'COM.311', 2, 'dental_technology', 2, 'HND1'),
  (328, 'Laboratory Management', 'GLT.301', 2, 'dental_technology', 2, 'HND1'),
  (329, 'Primary Oral Health Care', 'DTH.313', 2, 'dental_technology', 2, 'HND1'),
  (330, 'Partial Denture Prosthetics II', 'DTE.321', 2, 'dental_technology', 2, 'HND1'),
  (331, 'Orthodontic Technology I', 'DTE.322', 2, 'dental_technology', 2, 'HND1'),
  (332, 'Occlusion and Its Dysfunction', 'DTE.323', 2, 'dental_technology', 2, 'HND1'),
  (333, 'Introduction to Psychology', 'GNS.323', 2, 'dental_technology', 2, 'HND1'),
  (334, 'Biological Techniques I', 'STB.314', 2, 'dental_technology', 2, 'HND1'),
  (335, 'Use of English', 'GNS.302', 2, 'dental_technology', 2, 'HND1'),
  (336, 'Occlusion & Its Dysfunction (Practical)', 'DTE.323P', 2, 'dental_technology', 2, 'HND1'),
  (337, 'Orthodontics Technology I (Practical)', 'DTE.322P', 2, 'dental_technology', 2, 'HND1'),
  (338, 'Partial Denture Prosthetics II (Practical)', 'DTE.321P', 2, 'dental_technology', 2, 'HND1'),
  (339, 'Metallic Prosthetics Denture I', 'DTE.413', 2, 'dental_technology', 1, 'HND2'),
  (340, 'Office and Hospital Management', 'DTE.412', 2, 'dental_technology', 1, 'HND2'),
  (341, 'Science of Dental Materials III', 'DTE.414', 2, 'dental_technology', 1, 'HND2'),
  (342, 'Professional Ethics and Jurisprudence', 'DTE.415', 2, 'dental_technology', 1, 'HND2'),
  (343, 'Introduction of Maxillofacial Technology', 'DTE.421', 2, 'dental_technology', 1, 'HND2'),
  (344, 'Research Methodology', 'NUD.435', 2, 'dental_technology', 1, 'HND2'),
  (345, 'Biological Techniques II', 'STB.318', 2, 'dental_technology', 1, 'HND2'),
  (346, 'Introduction of Maxillofacial Technology (Practica', 'DTE.421P', 2, 'dental_technology', 1, 'HND2'),
  (347, 'Metallic Prosthetics Denture I (Practical)', 'DTE.413P', 2, 'dental_technology', 1, 'HND2'),
  (348, 'Biological Techniques II', 'STB.411', 2, 'dental_technology', 2, 'HND2'),
  (349, 'Microbiological Techniques I', 'STM.312', 2, 'dental_technology', 2, 'HND2'),
  (350, 'Entrepreneurship Development (Module I)', 'BAM.413', 2, 'dental_technology', 2, 'HND2'),
  (351, 'Metallic Prosthetics Denture III', 'DTE.423', 2, 'dental_technology', 2, 'HND2'),
  (352, 'Metallic Prosthetics Denture II', 'DTE.422', 2, 'dental_technology', 2, 'HND2'),
  (353, 'Project', 'DTE.424', 5, 'dental_technology', 2, 'HND2'),
  (354, 'Orthodontic Technology II', 'DTE.412', 2, 'dental_technology', 2, 'HND2'),
  (355, 'Complete Denture Prosthetic III', 'DTE.411', 2, 'dental_technology', 2, 'HND2'),
  (356, 'Metallic Prosthetics Denture II (Practical)', 'DTE.422P', 2, 'dental_technology', 2, 'HND2'),
  (357, 'Orthodontic Technology II (Practical)', 'DTE.412P', 2, 'dental_technology', 2, 'HND2'),
  (358, 'Metallic Prosthetics Denture III (Practical)', 'DTE.423P', 2, 'dental_technology', 2, 'HND2'),
  (359, 'Complete Denture Prosthetics III (Practical)', 'DTE.411P', 2, 'dental_technology', 2, 'HND2'),
  (360, 'Logic and Linear Algebra', 'MTH.111', 2, 'dental_therapy', 1, 'OND1'),
  (361, 'General and Physical Chemistry', 'BCH.111', 2, 'dental_therapy', 1, 'OND1'),
  (362, 'Mechanics and Properties of Mater', 'BPH.111', 2, 'dental_therapy', 1, 'OND1'),
  (363, 'Biology', 'BBA.111', 2, 'dental_therapy', 1, 'OND1'),
  (364, 'Introduction to Statistics', 'STA.111', 2, 'dental_therapy', 1, 'OND1'),
  (365, 'Communication in English', 'GNS.101', 2, 'dental_therapy', 1, 'OND1'),
  (366, 'Oral Hygiene', 'DTH.111', 2, 'dental_therapy', 1, 'OND1'),
  (367, 'Primary Health Care (PHC)', 'DTH.112', 2, 'dental_therapy', 1, 'OND1'),
  (368, 'Tooth Morphology', 'DTH.114', 2, 'dental_therapy', 1, 'OND1'),
  (369, 'Morphology and Physiology of Living Things', 'STB.111', 3, 'dental_therapy', 1, 'OND1'),
  (370, 'Tooth Carving', 'DTH.114P', 2, 'dental_therapy', 1, 'OND1'),
  (371, 'Algebra and Elementary Trigonometry', 'MTH.121', 2, 'dental_therapy', 2, 'OND1'),
  (372, 'General Laboratory Technique I', 'GLT.121', 2, 'dental_therapy', 2, 'OND1'),
  (373, 'Organic and Inorganic chemistry', 'BCH.121', 2, 'dental_therapy', 2, 'OND1'),
  (374, 'Physics (Optics, Wave, Electricity & Magnetism)', 'BPH.121', 2, 'dental_therapy', 2, 'OND1'),
  (375, 'Technical Drawing', 'PTD.111', 2, 'dental_therapy', 2, 'OND1'),
  (376, 'Communication in English', 'GNS.102', 2, 'dental_therapy', 2, 'OND1'),
  (377, 'Medical Sociology', 'GNS.124', 2, 'dental_therapy', 2, 'OND1'),
  (378, 'Biology', 'BBA.121', 2, 'dental_therapy', 2, 'OND1'),
  (379, 'Introduction Microbiology', 'STB.121', 2, 'dental_therapy', 2, 'OND1'),
  (380, 'Human Anatomy and Physiology I', 'DTH.115', 3, 'dental_therapy', 2, 'OND1'),
  (381, 'General Laboratory Techniques', 'GLT.211', 2, 'dental_therapy', 1, 'OND2'),
  (382, 'Oral Histology and Anatomy', 'DTH.211', 2, 'dental_therapy', 1, 'OND2'),
  (383, 'Oral Physiology', 'DTH.122', 2, 'dental_therapy', 1, 'OND2'),
  (384, 'Instrumentation Care and Maintenance', 'DTH.121', 2, 'dental_therapy', 1, 'OND2'),
  (385, 'Phantom Head (Practical)', 'DTH.212', 3, 'dental_therapy', 1, 'OND2'),
  (386, 'General Pathology I & II', 'DTH.214', 2, 'dental_therapy', 1, 'OND2'),
  (387, 'Introduction to Nursing Care', 'DTH.216', 2, 'dental_therapy', 1, 'OND2'),
  (388, 'Dental Radiography I', 'DTH.217', 2, 'dental_therapy', 1, 'OND2'),
  (389, 'Pharmacology', 'DTH.113', 1, 'dental_therapy', 1, 'OND2'),
  (390, 'Communication in English', 'GNS.201', 2, 'dental_therapy', 1, 'OND2'),
  (391, 'Citizenship Education', 'GNS.121', 2, 'dental_therapy', 1, 'OND2'),
  (392, 'VIVA VOCE', 'DTH.219', 2, 'dental_therapy', 1, 'OND2'),
  (393, 'First Aid and Dental emergencies', 'DTH.221', 2, 'dental_therapy', 2, 'OND2'),
  (394, 'Anatomy of Head and Neck', 'DTH.222', 2, 'dental_therapy', 2, 'OND2'),
  (395, 'Human Anatomy and Physiology II', 'DTH.223', 2, 'dental_therapy', 2, 'OND2'),
  (396, 'Dental Materials', 'DTH.224', 2, 'dental_therapy', 2, 'OND2'),
  (397, 'Clinical Practice I', 'DTH.225', 2, 'dental_therapy', 2, 'OND2'),
  (398, 'Seminar', 'DTH.226/227', 4, 'dental_therapy', 2, 'OND2'),
  (399, 'Phantom Head', 'DTH.228', 4, 'dental_therapy', 2, 'OND2'),
  (400, 'Instrumentation II', 'DTH.215', 2, 'dental_therapy', 2, 'OND2'),
  (401, 'VIVA VOCE', 'DTH.229', 2, 'dental_therapy', 2, 'OND2'),
  (402, 'Human Nutrition', 'NUD.122', 2, 'dental_therapy', 2, 'OND2'),
  (403, 'Medical emergencies', 'DTH.311', 2, 'dental_therapy', 1, 'HND1'),
  (404, 'Paedodontics', 'DTH.314', 2, 'dental_therapy', 1, 'HND1'),
  (405, 'Orthodontics', 'DTH.315', 2, 'dental_therapy', 1, 'HND1'),
  (406, 'Dental Radiography II', 'DTH.316', 2, 'dental_therapy', 1, 'HND1'),
  (407, 'Care of Hospital Patients', 'DTH.317', 2, 'dental_therapy', 1, 'HND1'),
  (408, 'Practical (Clinical)', 'DTH.318', 4, 'dental_therapy', 1, 'HND1'),
  (409, 'Oral Health Education', 'DTH.213', 2, 'dental_therapy', 1, 'HND1'),
  (410, 'Bacteriology', 'STM.311', 2, 'dental_therapy', 1, 'HND1'),
  (411, 'Human Nutrition', 'NUD.311', 2, 'dental_therapy', 1, 'HND1'),
  (412, 'Instrumentation (General)', 'STB.302', 2, 'dental_therapy', 1, 'HND1'),
  (413, 'Clinical Practice II', 'DTH.321', 3, 'dental_therapy', 2, 'HND1'),
  (414, 'Anesthesiology', 'DTH.322', 1, 'dental_therapy', 2, 'HND1'),
  (415, 'Instrumentation III', 'DTH.323', 2, 'dental_therapy', 2, 'HND1'),
  (416, 'Oral Pathology', 'DTH.324', 3, 'dental_therapy', 2, 'HND1'),
  (417, 'POHC/School Visit', 'DTH.313', 2, 'dental_therapy', 2, 'HND1'),
  (418, 'Practical (Clinical)', 'DTH.326', 4, 'dental_therapy', 2, 'HND1'),
  (419, 'Biological Techniques I', 'STB.314', 2, 'dental_therapy', 2, 'HND1'),
  (420, 'Human Nutrition', 'NUD.321', 2, 'dental_therapy', 2, 'HND1'),
  (421, 'Laboratory Management', 'GLT.301', 2, 'dental_therapy', 2, 'HND1'),
  (422, 'Introduction to Psychology', 'GNS.323', 2, 'dental_therapy', 2, 'HND1'),
  (423, 'Oral Diagnostic & Treatment Planning', 'DTH.325', 2, 'dental_therapy', 2, 'HND1'),
  (424, 'Oral Health Education III (School Visit)', 'DTH.411', 2, 'dental_therapy', 1, 'HND2'),
  (425, 'Office and Hospital Management', 'DTH.412', 2, 'dental_therapy', 1, 'HND2'),
  (426, 'Public Health Education and Community Dentistry', 'DTH.413', 2, 'dental_therapy', 1, 'HND2'),
  (427, 'Primary Health Care (PHC)', 'DTH.414', 2, 'dental_therapy', 1, 'HND2'),
  (428, 'Practical (Clinical)', 'DTH.415', 4, 'dental_therapy', 1, 'HND2'),
  (429, 'VIVA VOCE', 'DTH.416', 2, 'dental_therapy', 1, 'HND2'),
  (430, 'Ethics and Jurisprudence', 'DTE.415', 2, 'dental_therapy', 1, 'HND2'),
  (431, 'Biological Techniques II', 'STB.318', 2, 'dental_therapy', 1, 'HND2'),
  (432, 'Medical Statistics', 'STA.416', 2, 'dental_therapy', 1, 'HND2'),
  (433, 'Research Methodology', 'NUD.435', 2, 'dental_therapy', 1, 'HND2'),
  (434, 'Project', 'DTH.421', 5, 'dental_therapy', 2, 'HND2'),
  (435, 'Practical (Clinical)', 'DTH.422', 4, 'dental_therapy', 2, 'HND2'),
  (436, 'VIVA VOCE', 'DTH.423', 2, 'dental_therapy', 2, 'HND2'),
  (437, 'Biological Techniques III', 'STB.411', 2, 'dental_therapy', 2, 'HND2'),
  (438, 'Clinical Disease and Deity Therapy', 'NUD.322', 2, 'dental_therapy', 2, 'HND2'),
  (439, 'Microbiological Technique I', 'STM.312', 2, 'dental_therapy', 2, 'HND2'),
  (440, 'Entrepreneurship Development Programme', 'BAM.413', 2, 'dental_therapy', 2, 'HND2'),
  (441, 'Computer Programming', 'COM.311', 2, 'dental_therapy', 2, 'HND2');
