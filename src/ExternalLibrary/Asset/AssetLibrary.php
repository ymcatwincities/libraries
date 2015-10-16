<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Asset\AssetLibrary.
 */

namespace Drupal\libraries\ExternalLibrary\Asset;

use Drupal\libraries\ExternalLibrary\ExternalLibraryTrait;

/**
 * Provides a base asset library implementation.
 */
class AssetLibrary implements AssetLibraryInterface {

  use ExternalLibraryTrait;
  use SingleAssetLibraryTrait;

  /**
   * {@inheritdoc}
   */
  public function getCssAssets() {
    // @todo
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getJsAssets() {
    // @todo
    return [];
  }

}
