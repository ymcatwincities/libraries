<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\LibraryInterface.
 */

namespace Drupal\libraries\ExternalLibrary;


/**
 * Provides an interface for different types of external libraries.
 */
interface LibraryInterface {

  /**
   * Returns the ID of the library.
   *
   * @return string
   *   The library ID. This must be unique among all known libraries.
   *
   * @todo Define what constitutes a "known" library.
   */
  public function getId();

  /**
   * Returns the libraries dependencies, if any.
   *
   * @return array
   *   An array of library IDs of libraries that the library depends on.
   */
  public function getDependencies();

  /**
   * Creates an instance of the library from its definition.
   *
   * @param string $id
   *   The library ID.
   * @param array $definition
   *   The library definition array parsed from the definition JSON file.
   *
   * @return static
   *
   * @todo Consider passing in some stuff that might be useful.
   */
  public static function create($id, array $definition);

}
