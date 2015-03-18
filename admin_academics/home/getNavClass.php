<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 17-Mar-15
 * Time: 3:46 PM
 */

/**
 * @param array $currentPage An associative array containing strings that defines current page - used for css styling
 * of navs
 * @param String $section
 * @param String $value
 * @return string - A css class that will be used to style navs and links to show which page is currently been viewed.
 */
function getNavClass($currentPage, $section, $value)
{
  if ($currentPage) {
    switch ($section) {
      case 'nav':
        return $currentPage['title'] == $value ? 'expanded' : 'collapsed';

      case 'links':
        return $currentPage['title'] == $value ? 'active' : '';

      case 'link':
        return $currentPage['link'] == $value ? 'selected current' : '';
    }
  }
  return $section == 'nav' ? 'collapsed' : '';
}
