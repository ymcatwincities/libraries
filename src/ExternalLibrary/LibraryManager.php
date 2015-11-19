<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryManager.
 */

namespace Drupal\libraries\ExternalLibrary;
use Drupal\libraries\Extension\ExtensionHandlerInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryLoadingListenerInterface;
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface;
use Drupal\libraries\ExternalLibrary\Registry\LibraryRegistryInterface;

/**
 * Provides a manager for external libraries.
 */
class LibraryManager implements LibraryManagerInterface {

  /**
   * The library registry.
   *
   * @var \Drupal\libraries\ExternalLibrary\Registry\LibraryRegistryInterface
   */
  protected $registry;

  /**
   * The extension handler.
   *
   * @var \Drupal\libraries\Extension\ExtensionHandlerInterface
   */
  protected $extensionHandler;

  /**
   * Constructs an external library manager.
   *
   * @param \Drupal\libraries\ExternalLibrary\Registry\LibraryRegistryInterface $registry
   *   The library registry.
   * @param \Drupal\libraries\Extension\ExtensionHandlerInterface $extension_handler
   *   The extension handler.
   * @param \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface $php_file_loader
   *   The PHP file loader.
   */
  public function __construct(
    LibraryRegistryInterface $registry,
    ExtensionHandlerInterface $extension_handler,
    PhpFileLoaderInterface $php_file_loader
  ) {
    $this->registry = $registry;
    $this->extensionHandler = $extension_handler;
    $this->phpFileLoader = $php_file_loader;
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
    $library_type = $this->registry->getLibraryType($id);
    // @todo Throw an exception instead of silently failing.
    if ($library_type instanceof LibraryLoadingListenerInterface) {
      $library_type->onLibraryLoad($this->registry->getLibrary($id));
   }
  }

}
