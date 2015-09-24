<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Exception\LibraryExceptionTrait.
 */

namespace Drupal\libraries\ExternalLibrary\Exception;

/**
 * Provides a trait for library-related exceptions.
 */
trait LibraryExceptionTrait {

  /**
   * The library ID of the library that caused the exception.
   *
   * @var string
   */
  protected $libraryId;

  /**
   * Returns the library ID of the library that caused the exception.
   *
   * @return string
   *   The library ID.
   */
  public function getLibraryId() {
    return $this->libraryId;
  }

}
