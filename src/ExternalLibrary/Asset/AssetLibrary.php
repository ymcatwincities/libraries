<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Asset\AssetLibrary.
 */

namespace Drupal\libraries\ExternalLibrary\Asset;

use Drupal\libraries\ExternalLibrary\LibraryTrait;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryTrait;
use Drupal\libraries\ExternalLibrary\Remote\RemoteLibraryInterface;
use Drupal\libraries\ExternalLibrary\Remote\RemoteLibraryTrait;

/**
 * Provides a base asset library implementation.
 */
class AssetLibrary implements AssetLibraryInterface, LocalLibraryInterface, RemoteLibraryInterface {

  use
    LibraryTrait,
    LocalLibraryTrait,
    RemoteLibraryTrait,
    SingleAssetLibraryTrait,
    LocalRemoteAssetTrait
  ;

  /**
   * Construct an external library.
   *
   * @param string $id
   *   The library ID.
   * @param array $definition
   *   The library definition array parsed from the definition JSON file.
   */
  public function __construct($id, array $definition) {
    $this->id = (string) $id;
    // @todo Split this into proper properties.
    if (isset($definition['remote_url'])) {
      $this->remoteUrl = $definition['remote_url'];
    }
    if (isset($definition['css'])) {
      $this->cssAssets = $definition['css'];
    }
    if (isset($definition['js'])) {
      $this->jsAssets = $definition['js'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create($id, array $definition) {
    return new static($id, $definition);
  }

}
