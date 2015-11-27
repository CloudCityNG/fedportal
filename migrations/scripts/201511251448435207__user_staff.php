<?php


Class A201511251448435207
{
  public function up(PDO $db)
  {
    $db->exec(
      "CREATE TABLE staff_profile (
        `id`              INT      NOT NULL     AUTO_INCREMENT,
        username          VARCHAR(50) NOT NULL,
        password          VARCHAR(255) NOT NULL,
        first_name        VARCHAR(50) NULL,
        last_name         VARCHAR(50) NULL,
        middle_name       VARCHAR(50) NULL,
        date_of_birth     DATE NULL,
        marital_status    BOOLEAN NOT NULL DEFAULT 0,
        sex               VARCHAR(1) NULL,
        state_of_origin   VARCHAR(50) NULL,
        pic               VARCHAR(100) NULL,
        `created_at`      TIMESTAMP NOT NULL,
        `updated_at`      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `deleted_at`      TIMESTAMP NULL DEFAULT NULL,
        is_super_user     BOOLEAN NOT NULL DEFAULT 0,

        PRIMARY KEY (`id`)
      )"
    );

    $db->exec(
      "CREATE TABLE staff_capability (
        `id`              INT           NOT NULL     AUTO_INCREMENT,
        name              VARCHAR(100)  NOT NULL,
        code              VARCHAR(50)   NOT NULL,
        `created_at`      TIMESTAMP     NOT NULL,
        `updated_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `deleted_at`      TIMESTAMP     NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
      )"
    );

    $db->exec(
      "CREATE TABLE staff_capability_assign (
        `id`                    INT           NOT NULL     AUTO_INCREMENT,
        `staff_profile_id`      INT           NOT NULL,
        `staff_capability_id`   INT           NOT NULL,
        `created_at`            TIMESTAMP     NOT NULL,
        `updated_at`            TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `deleted_at`            TIMESTAMP     NULL DEFAULT NULL,

        PRIMARY KEY (`id`),
        FOREIGN KEY (`staff_profile_id`) REFERENCES `staff_profile` (`id`),
        FOREIGN KEY (`staff_capability_id`) REFERENCES `staff_capability` (`id`)
      )"
    );

    $db->exec(
      "INSERT INTO staff_capability (code, name, created_at) VALUES
        ('can_view_admin_page', 'Assess the admin page', NOW()),
        ('can_view_student_profile', 'Assess admin page for managing student profiles', NOW()),
        ('can_edit_student_profile', 'Edit student profile', NOW()),
        ('can_view_semester' , 'Assess admin page for managing academic semester', NOW()),
        ('can_create_semester' , 'Create a new semester', NOW()),
        ('can_edit_semester' , 'Modify and existing semester', NOW()),
        ('can_view_session' , 'Assess admin page for managing academic sessions', NOW()),
        ('can_create_session' , 'Create a new session', NOW()),
        ('can_edit_session' , 'Modify an existing session', NOW()),
        ('can_view_exams' , 'Assess the admin page for managing students exams and assessments', NOW()),
        ('can_gen_transcripts', 'Generate students transcripts', NOW()),
        ('can_publish_results', 'Publish students exam results so students can view them on their profile page.', NOW()),
        ('can_view_courses', 'Assess admin page for managing students courses', NOW())
      "
    );
  }

  public function down(PDO $db)
  {
  }
}
