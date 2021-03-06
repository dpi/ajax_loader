<?php

namespace Drupal\ajax_loader\Plugin\ajax_loader;

use Drupal\ajax_loader\ThrobberPluginBase;

/**
 * @Throbber(
 *   id = "throbber_chasing_dots",
 *   label = @Translation("Chasing dots")
 * )
 */
class ThrobberChasingDots extends ThrobberPluginBase {

  /**
   * @inheritdoc
   */
  protected function setMarkup() {
    return '<div class="ajax-throbber sk-chasing-dots">
              <div class="sk-child sk-dot1"></div>
              <div class="sk-child sk-dot2"></div>
            </div>';
  }

  /**
   * @inheritdoc
   */
  protected function setCssFile() {
    return $this->path . '/css/chasing-dots.css';
  }

}