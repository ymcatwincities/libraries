<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface.
 */

namespace Drupal\libraries\ExternalLibrary\Asset;

use Drupal\libraries\ExternalLibrary\ExternalLibraryInterface;

/**
 * Provides an interface for library with assets.
 *
 * @see \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryTrait
 *
 * @todo Explain
 */
interface AssetLibraryInterface extends ExternalLibraryInterface {

  /**
   * Gets the ID of the respective core asset library.
   *
   * @return string
   *
   * @todo Reconsider this method.
   */
  public function getAttachableAssetLibraryId();

  /**
   * Returns a core asset library array structure for this library.
   *
   * @return array
   *
   * @todo Document the return value.
   */
  public function toAttachableAssetLibrary();

}
