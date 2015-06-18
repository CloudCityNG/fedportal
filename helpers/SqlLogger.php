<?php

class SqlLogger
{

  /**
   * @var string - of the form:
   * purpose => [query: 'the database SQL prepared statement to be executed'], [params: 'the SQL query parameters']
   * @see @constructor
   */
  private $logMessage = '';

  /**
   * @var \Monolog\Logger - logger instance that will be used for logging
   */
  private $logger;

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
    $paramPart = $params ? ', [params: ' . print_r($params, true) . ']' : '';
    $this->logMessage = "{$purpose} =>  [query: {$query}]{$paramPart}";
    $logger->addInfo("Executing SQL: {$this->logMessage}");
    $this->logger = $logger;
  }

  /**
   * Every method that does database query will use a general log message format that looks like:
   * purpose => [query: 'the database SQL prepared statement to be executed'], [params: 'the SQL query parameters']
   * the params part of the message is however optional since not all SQL prepared statements use parameters
   *
   * @param string $purpose - a brief summary of what the method wishes to do or what we wish to retrieve from databse
   * @param $query - the SQL vanilla statement or prepared statement
   * @param array $params - the SQL prepared statement parameters - null if it does not use any parameter
   * @return string - in the form
   * purpose => [query: 'the database SQL prepared statement to be executed'], [params: 'the SQL query parameters']
   */
  public static function makeLogMessage($purpose, $query, array $params = null)
  {
    $paramPart = $params ? ', [params: ' . print_r($params, true) . ']' : '';
    return "{$purpose} =>  [query: {$query}]{$paramPart}";
  }

  public function dataRetrieved(array $databaseResult)
  {
    self::logDataRetrieved($this->logger, $this->logMessage, $databaseResult);
  }

  /**
   * When a database query succeeds and data is retrieved from database, log the retrieved data
   *
   * @param \Monolog\Logger $logger - the logger to be used for logging
   * @param string $logMessage - an optional log message, most likely the returned string from @method makeLogMessage
   * @param array $databaseResult - the result obtained from the database
   */
  public static function logDataRetrieved(\Monolog\Logger $logger, $logMessage = '', array $databaseResult)
  {
    $logMessage = $logMessage ? "{$logMessage}:, " : '';
    $logger->addInfo(
      "Results successfully obtained for executed SQL: {$logMessage}: result is: ", $databaseResult);
  }

  public function noData()
  {
    self::logNoData($this->logger, $this->logMessage);
  }

  /**
   * When a database query fails or no result is returned by the database, we log this fact
   *
   * @param \Monolog\Logger $logger - the logger to be used for logging
   * @param string $logMessage - a log message, most likely the returned string from @method makeLogMessage
   */
  public static function logNoData(\Monolog\Logger $logger, $logMessage)
  {
    $logger->addWarning("No data from database or database error for executed SQL: {$logMessage}");
  }

  public function statementSuccess(array $bindParams = null)
  {
    self::logStatementSuccess($this->logger, $this->logMessage, $bindParams);
  }

  /**
   * When a database query succeeds, log a success message
   *
   * @param \Monolog\Logger $logger - the logger to be used for logging
   * @param string $logMessage - an optional log message, most likely the returned string from @method makeLogMessage
   * @param array $bindParams - optional - used only if sql statement was executed with bind parameters
   */
  public static function logStatementSuccess(\Monolog\Logger $logger, $logMessage = '', array $bindParams = null)
  {
    $logMessage = $logMessage ? "{$logMessage}: " : '';
    $msg = "Statement execution succeeds for SQL: {$logMessage}";

    if ($bindParams) $msg .= ': bind params: ' . print_r($bindParams, true);

    $logger->addInfo($msg);
  }
}
