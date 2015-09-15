<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\KernelTestBase.
 */

namespace Drupal\Tests\libraries\Kernel;

use Drupal\Component\FileCache\ApcuFileCacheBackend;
use Drupal\Component\FileCache\FileCache;
use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\KernelTests\KernelTestBase as CoreKernelTestBase;
use Drupal\Core\Site\Settings;

/**
 * Provides an improved version of the core kernel test base class.
 */
class KernelTestBase extends CoreKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->initFileCache();
    parent::setUp();
  }

  /**
   * Initializes the FileCacheFactory component.
   *
   * We can not use the Settings object in a component, that's why we have to do
   * it here instead of \Drupal\Component\FileCache\FileCacheFactory.
   *
   * @see https://www.drupal.org/node/2553661
   */
  protected function initFileCache() {
    $configuration = Settings::get('file_cache');

    // Provide a default configuration, if not set.
    if (!isset($configuration['default'])) {
      $configuration['default'] = [
        'class' => FileCache::class,
        'cache_backend_class' => NULL,
        'cache_backend_configuration' => [],
      ];
      // @todo Use extension_loaded('apcu') for non-testbot
      //  https://www.drupal.org/node/2447753.
      if (function_exists('apc_fetch')) {
        $configuration['default']['cache_backend_class'] = ApcuFileCacheBackend::class;
      }
    }
    FileCacheFactory::setConfiguration($configuration);
    FileCacheFactory::setPrefix(Settings::getApcuPrefix('file_cache', $this->root));
  }

}
