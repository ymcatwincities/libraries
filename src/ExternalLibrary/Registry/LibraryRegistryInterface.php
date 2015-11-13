<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Registry\LibraryRegistryInterface.
 */

namespace Drupal\libraries\ExternalLibrary\Registry;


/**
 * Provides an interface for library registries.
 */
interface LibraryRegistryInterface {

  /**
   * Gets a library by its ID.
   *
   * @param string $id
   *   The library ID.
   *
   * @return \Drupal\libraries\ExternalLibrary\LibraryInterface
   *   The library.
   *
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryClassNotFoundException
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryDefinitionNotFoundException
   */
  public function getLibrary($id);

}
