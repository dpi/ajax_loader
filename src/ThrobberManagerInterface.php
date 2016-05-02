<?php

namespace Drupal\ajax_loader;

/**
 * Interface for the class that gathers the throbber plugins.
 */
interface ThrobberManagerInterface {

  /**
   * Get an options list suitable for form elements for throbber selection.
   *
   * @return array
   *   An array of options keyed by plugin ID with label values.
   */
  public function getThrobberOptionList();


  /**
   * Loads an instance of a plugin by given plugin id.
   * @param $plugin_id
   * @return object
   */
  public function loadThrobberInstance($plugin_id);

  /**
   * Loads all available throbbers.
   * @return mixed
   */
  public function loadAllThrobberInstances();

}
