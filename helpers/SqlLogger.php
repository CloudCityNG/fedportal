<?php

class SqlLogger
{
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

  /**
   * When a database query succeeds, log a success message
   *
   * @param \Monolog\Logger $logger - the logger to be used for logging
   * @param string $logMessage - an optional log message, most likely the returned string from @method makeLogMessage
   */
  public static function logStatementSuccess(\Monolog\Logger $logger, $logMessage = '')
  {
    $logMessage = $logMessage ? "{$logMessage}: " : '';
    $logger->addInfo("{$logMessage}: statement executed successfully");
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
    $logger->addInfo("{$logMessage}result is: ", $databaseResult);
  }

  /**
   * When a database query fails or no result is returned by the database, we log this fact
   *
   * @param \Monolog\Logger $logger - the logger to be used for logging
   * @param string $logMessage - a log message, most likely the returned string from @method makeLogMessage
   */
  public static function logNoData(\Monolog\Logger $logger, $logMessage)
  {
    $logger->addWarning("{$logMessage}: no data from database or database error.");
  }
}
