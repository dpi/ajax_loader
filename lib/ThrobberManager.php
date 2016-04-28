<?php

/**
 * Class ThrobberManager
 */
class ThrobberManager {

  /**
   * Returns all valid throbbers definied by other modules through hook_ajax_throbber().
   * @param array $throbbers
   * @return array
   */
  public static function processUserDefinedThrobbers($throbbers = array()) {
    if (!$throbbers) {
      $throbbers = module_invoke_all('ajax_throbber');
    }
    $valid_throbbers = array();

    foreach ($throbbers as $options) {
      if (isset($options['class'])) {
        // Validate the throbber.
        if ($valid_throbber = self::isValidThrobber($options['class'])) {
          array_push($valid_throbbers, $valid_throbber);
        }
      }
    }

    return $valid_throbbers;
  }

  /**
   * Validates throbber object.
   * @param $class_name
   * @return bool
   */
  public static function isValidThrobber($class_name) {

    $throbber = FALSE;
    if (class_exists($class_name)) {
      $throbber = new $class_name();
    }

    if (!$throbber instanceof ThrobberInterface) {
      // throbber has to be instance of ThrobberInterface.
      $throbber = FALSE;
    }
    elseif (empty($throbber->getLabel()) || empty($throbber->getCssFile()) || empty($throbber->getMarkup())) {
      // Properties have to be filled out.
      $throbber = FALSE;
    }

    return $throbber;
  }

}