<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryTrait.
 */

namespace Drupal\libraries\ExternalLibrary\Asset;

use Drupal\libraries\ExternalLibrary\ExternalLibraryInterface;

/**
 * Provides a trait for asset libraries.
 *
 * @see \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface
 * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryInterface
 */
trait AssetLibraryTrait {

  /**
   * Gets the ID of the respective core asset library.
   *
   * @return string
   *
   * @see \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface::getAttachableAssetLibraryId()
   */
  public function getAttachableAssetLibraryId() {
    return 'libraries/' . $this->getId();
  }

  /**
   * Returns a core library array structure for this library.
   *
   * @return array
   *
   * @see \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface::toAttachableAssetLibrary()
   *
   * @todo Document the return value.
   */
  public function toAttachableAssetLibrary() {
    $dependencies = array_map(function (ExternalLibraryInterface $dependency) {
      // Asset libraries depending on PHP file libraries, for example, are not
      // compatible with Drupal's render pipeline.
      // @todo Consider doing something other than an assertion.
      assert('$dependency instanceof \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface');
      /** @var \Drupal\libraries\ExternalLibrary\Asset\AssetLibraryInterface $dependency */
      return $dependency->getAttachableAssetLibraryId();
    }, $this->getDependencies());
    return [
      'version' => $this->getVersion(),
      'css' => $this->getCssAssets(),
      'js' => $this->getJsAssets(),
      'dependencies' => $dependencies,
    ];
  }

  /**
   * Returns the ID of the library.
   *
   * @return string
   *   The library ID. This must be unique among all known libraries.
   *
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryInterface::getId()
   */
  abstract public function getId();

  /**
   * Returns the currently installed version of the library.
   *
   * @return string
   *   The version string, for example 1.0, 2.1.4, or 3.0.0-alpha5.
   *
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryInterface::getVersion()
   */
  abstract protected function getVersion();

  /**
   * Returns the libraries dependencies, if any.
   *
   * @return array
   *   An array of library IDs of libraries that the library depends on.
   *
   * @see \Drupal\libraries\ExternalLibrary\ExternalLibraryInterface::getDependencies()()
   */
  abstract protected function getDependencies();

  /**
   * Gets the CSS assets attached to this library.
   *
   * @return array
   *   An array of CSS assets of the library following the core library CSS
   *   structure. The keys of the array must be among the SMACSS categories
   *   'base', 'layout, 'component', 'state', and 'theme'. The value of each
   *   category is in turn an array where the keys are the file paths of the CSS
   *   files and values are CSS options.
   *
   * @see https://smacss.com/
   *
   * @todo Expand documentation.
   * @todo Consider moving this back to AssetLibraryInterface
   */
  abstract protected function getCssAssets();

  /**
   * Gets the JavaScript assets attached to this library.
   *
   * @return array
   *   An array of JavaScript assets of the library. The keys of the array are
   *   the file paths of the JavaScript files and the values are JavaScript
   *   options.
   *
   * @todo Expand documentation.
   * @todo Consider moving this back to AssetLibraryInterface
   */
  abstract protected function getJsAssets();

}

