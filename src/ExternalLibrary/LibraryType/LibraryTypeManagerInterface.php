<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeManagerInterface.
 */

namespace Drupal\libraries\ExternalLibrary\LibraryType;

use Drupal\Component\Plugin\Factory\FactoryInterface;

/**
 * Provides an interface for library type managers.
 */
interface LibraryTypeManagerInterface extends FactoryInterface {

  /**
   * Gets the library class to use for a given library type.
   *
   * @param \Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface $library_type
   *   The library type to return the library class for.
   *
   * @return string
   *   The library class.
   */
  public function getLibraryClass(LibraryTypeInterface $library_type);

}

