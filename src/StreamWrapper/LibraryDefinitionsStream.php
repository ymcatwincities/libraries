<?php

/**
 * @file
 * Contains \Drupal\libraries\StreamWrapper\LibraryDefinitionsStream.
 */

namespace Drupal\libraries\StreamWrapper;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StreamWrapper\LocalStream;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

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
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs an external library registry.
   *
   * @todo Dependency injection.
   */
  public function __construct() {
    $this->configFactory = \Drupal::configFactory();
    $this->httpClient = \Drupal::httpClient();
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
    print 'Directory path: ' . $this->getConfig('local.path') . PHP_EOL;
    return $this->getConfig('local.path');
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    if ($this->getConfig('remote.enable')) {
      $url = $this->getConfig('remote.url');
      return $url . '/' . $this->uri;
    }
  }

  public function url_stat($uri, $flags) {
    $status = parent::url_stat($uri, $flags);
    if (($status === FALSE) && $this->getConfig('remote.enable')) {
      $url = $this->getConfig('remote.url');
      try {
        $response = $this->httpClient->request('GET', $url . '/' . $this->getTarget($uri));
        print 'Local path: ' . $this->getLocalPath($uri) . PHP_EOL;
        print 'Body: ' . $response->getBody() . PHP_EOL;
        $written = file_put_contents($this->getLocalPath($uri), $response->getBody());
        if ($written !== FALSE) {
          $status = parent::url_stat($uri, $flags);
        }
      }
      catch (GuzzleException $exception) {
        // $status is already FALSE, nothing to do.
      }
    }
    return $status;
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
