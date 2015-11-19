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

  /**
   * Returns the library type for a library ID.
   *
   * Note that the passed ID is not the ID of the library type, but the library
   * ID itself. Use the LibraryTypeManager to retrieve a library type given its
   * ID.
   *
   * @param string $id
   *   The ID of the external library.
   *
   * @return string|\Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface
   *   The library type.
   *
   * @see \Drupal\libraries\ExternalLibrary\LibraryTypeManager
   *
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryClassNotFoundException
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *
   * @todo Consider making this protected again, when this is moved to the
   *   LibraryManager.
   */
  public function getLibraryType($id);

}
