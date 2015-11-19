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
use Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface;
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
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *   The locator factory.
   * @param \Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLoaderInterface $php_file_loader
   *   The PHP file loader.
   */
  public function __construct(FactoryInterface $locator_factory, PhpFileLoaderInterface $php_file_loader) {
    $this->locatorFactory = $locator_factory;
    $this->phpFileLoader = $php_file_loader;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('plugin.manager.libraries.locator'),
      $container->get('libraries.php_file_loader')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    // @todo Remove the duplication with the annotation.
    return 'php_file';
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraryClass() {
    // @todo Make this alter-able.
    return 'Drupal\libraries\ExternalLibrary\PhpFile\PhpFileLibrary';
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
