<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\ExternalLibrary\Asset\AssetLibraryTest.
 */

namespace Drupal\Tests\libraries\Kernel\ExternalLibrary\Asset;

use Drupal\Component\FileCache\ApcuFileCacheBackend;
use Drupal\Component\FileCache\FileCache;
use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\libraries\ExternalLibrary\Asset\AssetLibrary;
use Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface;

/**
 * Tests that external asset libraries are registered as core asset libraries.
 *
 * @group libraries
 */
class AssetLibraryTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   *
   * \Drupal\libraries\Extension requires system_get_info() which is in
   * system.module.
   */
  public static $modules = ['libraries', 'libraries_test', 'system'];

  /**
   * The Drupal core library discovery.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->initFileCache();

    parent::setUp();

    $library = new AssetLibrary('test_asset_library');
    $registry = $this->prophesize(ExternalLibraryRegistryInterface::class);
    $registry->getLibrary('test_asset_library')->willReturn($library);
    $this->container->set('libraries.registry', $registry->reveal());

    $this->libraryDiscovery = $this->container->get('library.discovery');
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


  /**
   * {@inheritdoc}
   */
  public function testAssetLibrary() {
    $library = $this->libraryDiscovery->getLibraryByName('libraries', 'test_asset_library');
    $this->assertNotEquals(FALSE, $library);
    $this->assertTrue(is_array($library));
  }

}
