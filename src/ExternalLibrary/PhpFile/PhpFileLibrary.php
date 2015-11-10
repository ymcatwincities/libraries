<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibrary.
 */

namespace Drupal\libraries\ExternalLibrary\PhpFile;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\libraries\ExternalLibrary\Exception\LibraryNotInstalledException;
use Drupal\libraries\ExternalLibrary\ExternalLibraryTrait;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryTrait;

/**
 * Provides a base PHP file library implementation.
 */
class PhpFileLibrary implements PhpFileLibraryInterface {

  use ExternalLibraryTrait;
  use LocalLibraryTrait;

  /**
   * An array of PHP files for this library.
   *
   * @var array
   */
  protected $files = [];

  /**
   * Constructs a PHP file library.
   *
   * @param string $id
   *   The library ID.
   * @param array $files
   *   An array of PHP files for this library.
   */
  public function __construct($id, array $files) {
    $this->id = (string) $id;
    $this->files = $files;
  }

  /**
   * Creates an instance of the library from its definition.
   *
   * @param string $id
   *   The library ID.
   * @param array $definition
   *   The library definition array parsed from the definition JSON file.
   *
   * @return static
   */
  public static function create($id, array $definition) {
    $definition += ['files' => []];
    return new static($id, $definition['files']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getScheme() {
    return 'php-file';
  }

  /**
   * {@inheritdoc}
   */
  public function getPhpFiles() {
    if (!$this->isInstalled()) {
      throw new LibraryNotInstalledException($this);
    }

    foreach ($this->files as $file) {
      yield $this->getLocalPath() . DIRECTORY_SEPARATOR . $file;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLocator(FactoryInterface $locator_factory) {
    return $locator_factory->createInstance('stream', ['scheme' => 'php-file']);
  }

}
