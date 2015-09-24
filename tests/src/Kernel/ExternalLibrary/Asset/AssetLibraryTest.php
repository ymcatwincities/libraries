<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\ExternalLibrary\Asset\AssetLibraryTest.
 */

namespace Drupal\Tests\libraries\Kernel\ExternalLibrary\Asset;

use Drupal\Tests\libraries\Kernel\KernelTestBase;

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
    parent::setUp();

    /** @var \Drupal\Core\Extension\ModuleHandlerInterface $module_handler */
    $root = $this->container->get('app.root');
    $module_handler = $this->container->get('module_handler');
    $module_path = $module_handler->getModule('libraries')->getPath();

    $this->installConfig('libraries');
    /** @var \Drupal\Core\Config\ConfigFactoryInterface $config_factory */
    $config_factory = $this->container->get('config.factory');
    $config_factory->getEditable('libraries.settings')
      ->set('library_definitions.local.path', "$root/$module_path/tests/library_definitions")
      ->save();

    $this->libraryDiscovery = $this->container->get('library.discovery');
  }

  /**
   * Tests that an external asset library is registered as a core asset library.
   *
   * @covers \Drupal\libraries\Extension\Extension
   * @covers \Drupal\libraries\Extension\ExtensionHandler
   * @covers \Drupal\libraries\ExternalLibrary\Asset\AssetLibrary
   * @covers \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryTrait
   * @covers \Drupal\libraries\ExternalLibrary\ExternalLibraryManager
   * @covers \Drupal\libraries\ExternalLibrary\ExternalLibraryTrait
   * @covers \Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistry
   */
  public function testAssetLibrary() {
    $library = $this->libraryDiscovery->getLibraryByName('libraries', 'test_asset_library');
    $this->assertNotEquals(FALSE, $library);
    $this->assertTrue(is_array($library));
  }

}
