<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryType\LibraryLoadingListenerInterface.
 */

namespace Drupal\libraries\ExternalLibrary\LibraryType;

use Drupal\libraries\ExternalLibrary\LibraryInterface;

/**
 * An interface for library types that want to react to library instantiation.
 */
interface LibraryLoadingListenerInterface {

  /**
   * Reacts to the instantiation of a library.
   *
   * @param \Drupal\libraries\ExternalLibrary\LibraryInterface $library
   *   The library that is being instantiated.
   */
  public function onLibraryLoad(LibraryInterface $library);

}

