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
