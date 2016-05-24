<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Asset\AssetLibrary.
 */

namespace Drupal\libraries\ExternalLibrary\Asset;

use Drupal\libraries\ExternalLibrary\Exception\UnknownLibraryVersionException;
use Drupal\libraries\ExternalLibrary\LibraryTrait;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryTrait;
use Drupal\libraries\ExternalLibrary\Remote\RemoteLibraryInterface;
use Drupal\libraries\ExternalLibrary\Remote\RemoteLibraryTrait;
use Drupal\libraries\ExternalLibrary\Version\VersionedLibraryInterface;
use Drupal\libraries\ExternalLibrary\Version\VersionedLibraryTrait;

/**
 * Provides a base asset library implementation.
 */
class AssetLibrary implements AssetLibraryInterface, VersionedLibraryInterface, LocalLibraryInterface, RemoteLibraryInterface {

  use
    LibraryTrait,
    LocalLibraryTrait,
    RemoteLibraryTrait,
    SingleAssetLibraryTrait,
    LocalRemoteAssetTrait,
    VersionedLibraryTrait
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
    // @todo Split this into a generic trait.
    if (isset($definition['version_detector'])) {
      // @todo Validate the sub-keys.
      $this->versionDetector = $definition['version_detector'];
    }
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
