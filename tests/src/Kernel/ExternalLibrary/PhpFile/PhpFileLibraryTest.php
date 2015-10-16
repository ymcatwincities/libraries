<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\ExternalLibrary\PhpFile\PhpFileLibraryTest.
 */

namespace Drupal\Tests\libraries\Kernel\ExternalLibrary\PhpFile;

use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibraryInterface;
use Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface;
use Drupal\Tests\libraries\Kernel\ExternalLibraryKernelTestBase;

/**
 * Tests that the external library manager properly loads PHP file libraries.
 *
 * @group libraries
 */
class PhpFileLibraryTest extends ExternalLibraryKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['libraries', 'libraries_test'];

  /**
   * The external library manager.
   *
   * @var \Drupal\libraries\ExternalLibrary\ExternalLibraryManagerInterface
   */
  protected $externalLibraryManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $root = $this->container->get('app.root');
    /** @var \Drupal\Core\Extension\ModuleHandlerInterface $module_handler */
    $module_handler = $this->container->get('module_handler');
    $module_path = $module_handler->getModule('libraries')->getPath();

    $library = $this->prophesize(PhpFileLibraryInterface::class);
    $library->getPhpFiles()->willReturn([
      $root . '/' . $module_path . '/tests/example/example_1.php',
    ]);
    $registry = $this->prophesize(ExternalLibraryRegistryInterface::class);
    $registry->getLibrary('test_php_file_library')->willReturn($library->reveal());
    $this->container->set('libraries.registry', $registry->reveal());

    $this->externalLibraryManager = $this->container->get('libraries.manager');
  }

  /**
   * Tests that the external library manager properly loads PHP file libraries.
   *
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryManager
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryTrait
   * @see \Drupal\libraries\ExternalLibrary\PhpFile\PhpRequireLoader
   */
  public function testPhpFileLibrary() {
    $function_name = '_libraries_test_example_1';
    if (function_exists($function_name)) {
      $this->markTestSkipped('Cannot test file inclusion if the file to be included has already been included prior.');
      return;
    }

    $this->assertFalse(function_exists($function_name));
    $this->externalLibraryManager->load('test_php_file_library');
    $this->assertTrue(function_exists($function_name));
  }

}
