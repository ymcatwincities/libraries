<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\ExternalLibraryManager.
 */

namespace Drupal\libraries\ExternalLibrary;
use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\libraries\Extension\ExtensionHandlerInterface;
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibraryInterface;
use Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface;

/**
 * Provides a manager for external libraries.
 */
class ExternalLibraryManager implements ExternalLibraryManagerInterface {

  /**
   * The library registry.
   *
   * @var \Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface
   */
  protected $registry;

  /**
   * The extension handler.
   *
   * @var \Drupal\libraries\Extension\ExtensionHandlerInterface
   */
  protected $extensionHandler;

  /**
   * The PHP file loader.
   *
   * @var \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface
   */
  protected $phpFileLoader;

  /**
   * Constructs an external library manager.
   *
   * @param \Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface $registry
   *   The library registry.
   * @param \Drupal\libraries\Extension\ExtensionHandlerInterface $extension_handler
   *   The extension handler.
   */
  public function __construct(
    ExternalLibraryRegistryInterface $registry,
    ExtensionHandlerInterface $extension_handler
  ) {
    $this->registry = $registry;
    $this->extensionHandler = $extension_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequiredLibraries() {
    $libraries = [];
    foreach ($this->extensionHandler->getExtensions() as $extension) {
      foreach ($extension->getLibraryDependencies() as $library_id) {
        // Do not bother instantiating a library multiple times.
        if (!isset($libraries[$library_id])) {
          $libraries[$library_id] = $this->registry->getLibrary($library_id);
        }
      }
    }

    return $libraries;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $library = $this->registry->getLibrary($id);
    if ($library instanceof PhpFileLibraryInterface) {
      foreach ($library->getPhpFiles() as $file) {
        $this->phpFileLoader->load($file);
      }
    }
  }

}
