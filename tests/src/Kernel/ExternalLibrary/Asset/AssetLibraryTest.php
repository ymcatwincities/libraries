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
use Drupal\Tests\libraries\Kernel\KernelTestBase;
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
    parent::setUp();

    $library = new AssetLibrary('test_asset_library');
    $registry = $this->prophesize(ExternalLibraryRegistryInterface::class);
    $registry->getLibrary('test_asset_library')->willReturn($library);
    $this->container->set('libraries.registry', $registry->reveal());

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
   */
  public function testAssetLibrary() {
    $library = $this->libraryDiscovery->getLibraryByName('libraries', 'test_asset_library');
    $this->assertNotEquals(FALSE, $library);
    $this->assertTrue(is_array($library));
  }

}
