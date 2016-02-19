<?php

class SqlLogger
{

  /**
   * @var string - of the form:
   * purpose => [query: 'the database SQL prepared statement to be executed'], [params: 'the SQL query parameters']
   * @see @constructor
   */
  private $logMessage = 'Executing SQL: ';

  /**
   * @var \Monolog\Logger - logger instance that will be used for logging
   */
  private $logger;

  /**
   * @var string - The user executing the sql statement. Will be empty if there is no session
   */
  private $user;

  /**
   * @var array - The context to Monolog Logger object
   */
  private $loggerContext;

  /**
   * @see @method makeLogMessage
   *
   * @param \Monolog\Logger $logger
   * @param $purpose
   * @param $query
   * @param array|null $params
   *
   * @constructor
   */
  public function __construct(\Monolog\Logger $logger, $purpose, $query, array $params = null)
  {
    $this->user = self::setSession();
    $this->loggerContext = ['purpose_of_sql' => $purpose, 'user' => $this->user, 'sql_query' => $query];

    if (is_array($params)) $this->loggerContext['sql_params'] = $params;

    $logger->addInfo('Executing SQL: ', $this->loggerContext);
    $this->logger = $logger;
  }

  private static function setSession()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION[USER_AUTH_SESSION_KEY])) {
      return json_decode($_SESSION[USER_AUTH_SESSION_KEY], true)['username'];
    }

    return '';
  }

  /**
   * Log the result of a database query
   * @param mixed $databaseResult - the result of the database query
   * @param array|null $hiddenParams - if specified, the values of the array entries will be set to 'hidden' in the
   * database result before logging. This is useful for hiding such things as user password
   */
  public function dataRetrieved($databaseResult, array $hiddenParams = null)
  {
    if ($hiddenParams) {
      foreach ($hiddenParams as $hiddenParam) {
        $databaseResult[$hiddenParam] = 'HIDDEN';
      }
    }

    $this->loggerContext['executed_sql_result'] = $databaseResult;

    $this->logger->addInfo("Results successfully obtained for executed SQL:", $this->loggerContext);
  }

  public function noData()
  {
    $this->logger->addWarning(
      "No data from database or database error for executed SQL:",
      $this->loggerContext
    );
  }

  public function statementSuccess(array $bindParams = null)
  {
    if ($bindParams) $this->loggerContext['sql_bind_params'] = $bindParams;

    $this->logger->addInfo(
      "Statement execution succeeds for SQL:", $this->loggerContext
    );
  }
}
