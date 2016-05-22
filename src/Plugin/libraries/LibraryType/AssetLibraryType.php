<?php

/**
 * @file
 * Contains \Drupal\libraries\Plugin\libraries\LibraryType\AssetLibraryType.
 */

namespace Drupal\libraries\Plugin\libraries\LibraryType;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\libraries\ExternalLibrary\Asset\AssetLibrary;
use Drupal\libraries\ExternalLibrary\LibraryInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryCreationListenerInterface;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryTypeInterface;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;
use Drupal\libraries\ExternalLibrary\Utility\IdAccessorTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @LibraryType("asset")
 */
class AssetLibraryType implements
  LibraryTypeInterface,
  LibraryCreationListenerInterface,
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
   * Constructs the asset library type.
   *
   * @param string $plugin_id
   *   The plugin ID taken from the class annotation.
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *   The locator factory.
   */
  public function __construct($plugin_id, FactoryInterface $locator_factory) {
    $this->id = $plugin_id;
    $this->locatorFactory = $locator_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $container->get('plugin.manager.libraries.locator'));
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraryClass() {
    return AssetLibrary::class;
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
