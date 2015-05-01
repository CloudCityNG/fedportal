<?php
include_once(__DIR__ . '/../../helpers/databases.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');


class ConfirmPinController1
{
  private $reg_no;

  private $pin;

  private $password;

  private $confirm_password;

  private $email;

  private static $LOG_NAME = "Confirm-pin";

  function __construct($post)
  {
    $log = get_logger(self::$LOG_NAME);

    $this->password = trim($post['password']);

    $this->confirm_password = trim($post['confirm-password']);

    $post['password'] = 'hidden';
    $post['confirm_password'] = 'hidden';

    $log->addInfo("Instantiating ConfirmPinController with post data", $post);

    $this->pin = trim($post['pin']);

    $this->reg_no = trim($post['reg_no']);

    $this->email = trim($post['email']);

  }

  public function confirm()
  {
    if (!$this->validate_post_data()) {
      return false;
    }

    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $log->addInfo(
      "About to confirm if student with reg no $this->reg_no
       and email $this->email is signing up with the correct pin $this->pin."
    );

    try {
      $stmt = $db->prepare(
        "UPDATE pin_table SET
         number = ?, pass = ?, email = ?
         WHERE number = ? AND pass IS NULL"
      );

      $stmt->execute([
        $this->reg_no,
        $this->password,
        $this->email,
        $this->pin
      ]);

      if ($stmt->rowCount()) {

        $log->addInfo("Pin successfully confirmed for student with reg no $this->reg_no");

        $stmt->closeCursor();

        return true;

      } else {
        $log->addInfo("Pin confirmation failed for student with reg no $this->reg_no.");
      }

    } catch (PDOException $e) {

      logPdoException(
        $e,

        "Error occurred while trying to confirm if student with reg no $this->reg_no
         and email $this->email is signing up with the correct pin $this->pin",

        $log
      );

    }

    return false;
  }

  private function validate_post_data()
  {

    return (
      ($this->password === $this->confirm_password) &&
      !empty($this->pin) &&
      !empty($this->reg_no) &&
      !empty($this->password) &&
      !empty($this->email)
    );
  }

}
