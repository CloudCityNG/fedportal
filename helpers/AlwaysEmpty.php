<?php

/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 13-Feb-15
 * Time: 7:26 PM
 */
class AlwaysEmpty
{
  function __get($name)
  {
    return '';
  }
}