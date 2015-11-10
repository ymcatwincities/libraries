<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\ExternalLibrary\PhpFile\PhpFileLibraryTest.
 */

namespace Drupal\Tests\libraries\Kernel\ExternalLibrary\PhpFile;

use Drupal\libraries\ExternalLibrary\Exception\LibraryClassNotFoundException;
use Drupal\libraries\ExternalLibrary\Exception\LibraryDefinitionNotFoundException;
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibrary;
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

    $this->externalLibraryManager = $this->container->get('libraries.manager');

    $this->container->set('stream_wrapper.php_library_files', new TestPhpLibraryFilesStream());
  }

  /**
   * Tests that library metadata is correctly gathered.
   */
  public function testMetadata() {
    try {
      /** @var \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibrary $library */
      $library = $this->externalLibraryRegistry->getLibrary('test_php_file_library');
      $this->assertInstanceOf(PhpFileLibrary::class, $library);

      $this->assertEquals('test_php_file_library', $library->getId());
      $expected = [$this->modulePath . DIRECTORY_SEPARATOR . 'tests/libraries/test_php_file_library/test_php_file_library.php'];
      $this->assertEquals($expected, $library->getPhpFiles());
    }
    catch (LibraryClassNotFoundException $exception) {
      $this->fail();
    }
    catch (LibraryDefinitionNotFoundException $exception) {
      $this->fail();
    }
  }

  /**
   * Tests that the external library manager properly loads PHP files.
   *
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryManager
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryTrait
   * @see \Drupal\libraries\ExternalLibrary\PhpFile\PhpRequireLoader
   */
  public function testFileLoading() {
    $function_name = '_libraries_test_php_function';
    if (function_exists($function_name)) {
      $this->markTestSkipped('Cannot test file inclusion if the file to be included has already been included prior.');
      return;
    }

    $this->assertFalse(function_exists($function_name));
    $this->externalLibraryManager->load('test_php_file_library');
    $this->assertTrue(function_exists($function_name));
  }

}
