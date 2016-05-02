<?php

namespace Drupal\ajax_loader;

use Drupal\Component\Plugin\Mapper\MapperInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Gathers the throbber plugins.
 */
class ThrobberManager extends DefaultPluginManager implements ThrobberManagerInterface, MapperInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ajax_loader', $namespaces, $module_handler, 'Drupal\ajax_loader\ThrobberPluginInterface', 'Drupal\ajax_loader\Annotation\Throbber');
  }

  /**
   * {@inheritdoc}
   */
  public function getThrobberOptionList() {
    $options = [];
    foreach ($this->getDefinitions() as $definition) {
      $options[$definition['id']] = $definition['label'];
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function loadThrobberInstance($plugin_id) {
    return $this->createInstance($plugin_id);
  }

  /**
   * {@inheritdoc}
   */
  public function loadAllThrobberInstances(){
    $throbbers = array();
    foreach ($this->getDefinitions() as $definition) {
      array_push($throbbers, $this->createInstance($definition['id']));
    }

    return $throbbers;
  }

}
