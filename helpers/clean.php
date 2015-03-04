<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 31-Jan-15
 * Time: 6:30 PM
 * @param $post_input
 * @return string
 */
//Function to sanitize values received from the form. Prevents SQL injection

function clean($post_input)
{
  return trim($post_input);
}
