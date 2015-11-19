<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeManager.
 */

namespace Drupal\libraries\ExternalLibrary\LibraryType;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides a plugin manager for library type plugins.
 */
class LibraryTypeManager extends DefaultPluginManager {

  /**
   * Constructs a locator manager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/libraries/LibraryType', $namespaces, $module_handler, 'Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface', 'Drupal\libraries\Annotation\LibraryType');
    $this->alterInfo('libraries_library_type_info');
    $this->setCacheBackend($cache_backend, 'libraries_library_type_info');
  }

}
