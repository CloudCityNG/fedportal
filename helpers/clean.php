<?php
//Function to sanitize values received from the form. Prevents SQL injection

function clean($post_input)
{
  return trim($post_input);
}
