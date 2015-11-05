<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface.
 */

namespace Drupal\libraries\ExternalLibrary\Local;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\libraries\ExternalLibrary\ExternalLibraryInterface;

/**
 * Provides an interface for local libraries.
 *
 * Local libraries are libraries that can be found on the filesystem. If the
 * library files can be found in the filesystem a library is considered
 * installed and its library path can be retrieved.
 *
 * A library not being installed can have a different meaning depending on the
 * library type. A PHP file library. for example, can not be loaded if it is not
 * installed whereas an asset library can be loaded from a remote source if one
 * is provided and that is permitted by the configuration.
 */
interface LocalLibraryInterface extends ExternalLibraryInterface {

  /**
   * Checks whether the library is installed.
   *
   * @return bool
   *   TRUE if the library is installed; FALSE otherwise;
   */
  public function isInstalled();

  /**
   * Marks the library as uninstalled.
   *
   * A corresponding method to mark the library as installed is not provided as
   * an installed library should have a library path, so that
   * LocalLibraryInterface::setLibraryPath() can be used instead.
   *
   * @return $this
   *
   * @see \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface::isInstalled()
   * @see \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface::setLibraryPath()
   */
  public function setUninstalled();

  /**
   * Gets the path to the library.
   *
   * @return string
   *   The absolute path to the library on the filesystem.
   *
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryNotInstalledException
   *
   * @see \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface::setLibraryPath()
   */
  public function getLibraryPath();

  /**
   * Sets the library path of the library.
   *
   * @param string $path
   *   The path to the library.
   *
   * @see \Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface::getLibraryPath()
   */
  public function setLibraryPath($path);

  /**
   * Gets the locator of this library using the locator factory.
   *
   * Because determining the installation status and library path of a library
   * is not specific to any library or even any library type, this logic is
   * offloaded to separate locator objects.
   *
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *
   * @return \Drupal\libraries\ExternalLibrary\Local\LocatorInterface
   *
   * @see \Drupal\libraries\ExternalLibrary\Local\LocatorInterface
   */
  public function getLocator(FactoryInterface $locator_factory);

}
