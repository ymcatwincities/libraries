<?php

/**
 * @file
 * Contains \Drupal\libraries\Plugin\libraries\LibraryType\PhpFileLibraryType.
 */

namespace Drupal\libraries\Plugin\libraries\LibraryType;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\libraries\ExternalLibrary\LibraryInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryCreationListenerInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryLoadingListenerInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface;
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibrary;
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface;
use Drupal\libraries\ExternalLibrary\Utility\IdAccessorTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @LibraryType("php_file")
 */
class PhpFileLibraryType implements
  LibraryTypeInterface,
  LibraryCreationListenerInterface,
  LibraryLoadingListenerInterface,
  ContainerFactoryPluginInterface
{

  use IdAccessorTrait;

  /**
   * The locator factory.
   *
   * @var \Drupal\Component\Plugin\Factory\FactoryInterface
   */
  protected $locatorFactory;

  /**
   * The PHP file loader.
   *
   * @var \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface
   */
  protected $phpFileLoader;

  /**
   * Constructs the PHP file library type.
   *
   * @param string $plugin_id
   *   The plugin ID taken from the class annotation.
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *   The locator factory.
   * @param \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface $php_file_loader
   *   The PHP file loader.
   */
  public function __construct($plugin_id, FactoryInterface $locator_factory, PhpFileLoaderInterface $php_file_loader) {
    $this->id = $plugin_id;
    $this->locatorFactory = $locator_factory;
    $this->phpFileLoader = $php_file_loader;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $container->get('plugin.manager.libraries.locator'),
      $container->get('libraries.php_file_loader')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraryClass() {
    return PhpFileLibrary::class;
  }

  /**
   * {@inheritdoc}
   */
  public function onLibraryCreate(LibraryInterface $library) {
    /** @var \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibraryInterface $library */
    $library->getLocator($this->locatorFactory)->locate($library);
  }

  /**
   * {@inheritdoc}
   */
  public function onLibraryLoad(LibraryInterface $library) {
    /** @var \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibraryInterface $library */
    foreach ($library->getPhpFiles() as $file) {
      $this->phpFileLoader->load($file);
    }
  }

}
