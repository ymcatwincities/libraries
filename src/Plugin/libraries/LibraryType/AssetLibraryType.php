<?php

/**
 * @file
 * Contains \Drupal\libraries\Plugin\libraries\LibraryType\AssetLibraryType.
 */

namespace Drupal\libraries\Plugin\libraries\LibraryType;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\libraries\ExternalLibrary\LibraryInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryCreationListenerInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @LibraryType("asset")
 */
class AssetLibraryType implements
  LibraryTypeInterface,
  LibraryCreationListenerInterface,
  ContainerFactoryPluginInterface
{

  /**
   * The locator factory.
   *
   * @var \Drupal\Component\Plugin\Factory\FactoryInterface
   */
  protected $locatorFactory;

  /**
   * Constructs the PHP file library type.
   *
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *   The locator factory.
   */
  public function __construct(FactoryInterface $locator_factory) {
    $this->locatorFactory = $locator_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($container->get('plugin.manager.libraries.locator'));
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    // @todo Remove the duplication with the annotation.
    return 'asset';
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraryClass() {
    // @todo Make this alter-able.
    return 'Drupal\libraries\ExternalLibrary\Asset\AssetLibrary';
  }

  /**
   * {@inheritdoc}
   */
  public function onLibraryCreate(LibraryInterface $library) {
    // The default implementation of asset libraries checks locally for library
    // files, but this is not required.
    if ($library instanceof LocalLibraryInterface) {
      $library->getLocator($this->locatorFactory)->locate($library);
    }
  }

}
