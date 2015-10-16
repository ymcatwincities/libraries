<?php

/**
 * @file
 * Contains \Drupal\libraries\StreamWrapper\LibraryDefinitionsStream.
 */

namespace Drupal\libraries\StreamWrapper;

use Drupal\Core\StreamWrapper\LocalStream;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;

/**
 * Provides a stream wrapper for library definitions.
 *
 * Can be used with the 'library-definitions' scheme, for example
 * 'library-definitions://example.json'.
 *
 * @see \Drupal\locale\StreamWrapper\TranslationsStream
 */
class LibraryDefinitionsStream extends LocalStream {

  /**
   * The config factory
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs an external library registry.
   *
   * @todo Dependency injection.
   */
  public function __construct() {
    $this->configFactory = \Drupal::configFactory();
  }

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return StreamWrapperInterface::LOCAL_HIDDEN;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return t('Library definitions');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Provides access to library definition files fetched from the remote canonical library registry.');
  }

  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath() {
    return $this->getConfig('local.path');
  }

  /**
   * {@inheritdoc}
   */
    function getExternalUrl() {
      throw new \LogicException('Library definitions should not be public.');
    }

  /**
   * Fetches a configuration value from the library definitions configuration.
   * @param $key
   *   The configuration key to fetch.
   *
   * @return array|mixed|null
   *   The configuration value.
   */
  protected function getConfig($key) {
    return $this->configFactory
      ->get('libraries.settings')
      ->get("library_definitions.$key");
  }

}
