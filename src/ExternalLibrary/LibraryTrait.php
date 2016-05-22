<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryTrait.
 */

namespace Drupal\libraries\ExternalLibrary;

use Drupal\libraries\ExternalLibrary\Utility\IdAccessorTrait;

/**
 * Provides a base external library implementation.
 */
trait LibraryTrait {

  use IdAccessorTrait;

  /**
   * Returns the currently installed version of the library.
   *
   * @return string
   *   The version string, for example 1.0, 2.1.4, or 3.0.0-alpha5.
   *
   * @see \Drupal\libraries\ExternalLibrary\LibraryInterface::getVersion()
   */
  public function getVersion() {
    // @todo Turn into something useful and split into some other trait.
    return '1.0';
  }

  /**
   * Returns the libraries dependencies, if any.
   *
   * @return array
   *   An array of library IDs of libraries that the library depends on.
   *
   * @see \Drupal\libraries\ExternalLibrary\LibraryInterface::getDependencies()
   */
  public function getDependencies() {
    // @todo Turn into something useful and split into some other trait.
    return [];
  }

}
