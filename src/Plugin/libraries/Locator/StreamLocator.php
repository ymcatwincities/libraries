<?php

/**
 * @file
 * Contains \Drupal\libraries\Plugin\libraries\Locator\StreamLocatorTrait.
 */

namespace Drupal\libraries\Plugin\libraries\Locator;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;
use Drupal\libraries\ExternalLibrary\Local\LocatorInterface;
use Drupal\libraries\Plugin\MissingPluginConfigurationException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a locator utilizing a stream wrapper.
 *
 * It makes the following assumptions:
 * - The library files can be accessed using a specified stream.
 * - The stream wrapper is local (i.e. it returns a proper value in its
 *   realpath() method).
 * - The first component of the file URIs are the library IDs (i.e. file URIs
 *   are of the form: scheme://library-id/path/to/file/filename).
 *
 * @Locator("stream")
 *
 * @see \Drupal\libraries\ExternalLibrary\Local\LocatorInterface
 */
class StreamLocator implements LocatorInterface, ContainerFactoryPluginInterface {

  /**
   * The file system helper.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystemHelper;

  /**
   * The scheme of the stream wrapper.
   *
   * @var string
   */
  protected $scheme;

  /**
   * Constructs a stream locator.
   *
   * @param \Drupal\Core\File\FileSystemInterface $file_system_helper
   *   The file system helper.
   * @param string $scheme
   *   The scheme of the stream wrapper.
   */
  public function __construct(FileSystemInterface $file_system_helper, $scheme) {
    $this->fileSystemHelper = $file_system_helper;
    $this->scheme = (string) $scheme;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    if (!isset($configuration['scheme'])) {
      throw new MissingPluginConfigurationException($plugin_id, $plugin_definition, $configuration, 'scheme');
    }
    return new static($container->get('file_system'), $configuration['scheme']);
  }


  /**
   * Locates a library.
   *
   * @param \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface $library
   *   The library to locate.
   *
   * @see \Drupal\libraries\ExternalLibrary\Local\LocatorInterface::locate()
   */
  public function locate(LocalLibraryInterface $library) {
    $path = $this->fileSystemHelper->realpath($this->getUri($library));
    if (is_dir($path) && is_readable($path)) {
      $library->setLibraryPath($path);
    }
    else {
      $library->setUninstalled();
    }
  }

  /**
   * Gets the URI of a library.
   *
   * @param \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface $library
   *   The library.
   *
   * @return string
   *   The URI of the library.
   */
  protected function getUri(LocalLibraryInterface $library) {
    return $this->scheme . '://' . $library->getId();
  }

}
