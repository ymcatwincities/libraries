<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Registry\LibraryRegistry.
 */

namespace Drupal\libraries\ExternalLibrary\Registry;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Component\Serialization\SerializationInterface;
use Drupal\libraries\ExternalLibrary\Exception\LibraryClassNotFoundException;
use Drupal\libraries\ExternalLibrary\Exception\LibraryDefinitionNotFoundException;
use Drupal\libraries\ExternalLibrary\Local\LocalLibraryInterface;

/**
 * Provides an implementation of a registry of external libraries.
 *
 * @todo Allow for JavaScript CDN's, Packagist, etc. to act as library
 *   registries.
 */
class LibraryRegistry implements LibraryRegistryInterface {

  /**
   * The serializer for the library definition files.
   *
   * @var \Drupal\Component\Serialization\SerializationInterface
   */
  protected $serializer;

  /**
   * The library locator factory.
   *
   * @var \Drupal\Component\Plugin\Factory\FactoryInterface
   */
  protected $locatorFactory;

  /**
   * Constructs a registry of external libraries.
   *
   * @param \Drupal\Component\Serialization\SerializationInterface $serializer
   *   The serializer for the library definition files.
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $locator_factory
   *   The library locator factory.
   */
  public function __construct(
    SerializationInterface $serializer,
    FactoryInterface $locator_factory
  ) {
    $this->serializer = $serializer;
    $this->locatorFactory = $locator_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getLibrary($id) {
    if (!$this->hasDefinition($id)) {
      throw new LibraryDefinitionNotFoundException($id);
    }
    $definition = $this->getDefinition($id);
    $class = $this->getClass($id, $definition);
    $library = $class::create($id, $definition);

    // @todo Dispatch an event to provide loose coupling
    if ($library instanceof LocalLibraryInterface) {
      $library->getLocator($this->locatorFactory)->locate($library);
    }

    return $library;
  }

  /**
   * Checks whether a library definition exists for the given ID.
   *
   * @param string $id
   *   The library ID to check for.
   *
   * @return bool
   *  TRUE if the library definition exists; FALSE otherwise.
   */
  protected function hasDefinition($id) {
    return file_exists($this->getFileUri($id));
  }

  /**
   * Returns the library definition for the given ID.
   *
   * @param string $id
   *   The library ID to retrieve the definition for.
   *
   * @return array
   *   The library definition array parsed from the definition JSON file.
   */
  protected function getDefinition($id) {
    return $this->serializer->decode(file_get_contents($this->getFileUri($id)));
  }

  /**
   * Returns the file URI of the library definition file for a given library ID.
   *
   * @param $id
   *   The ID of the external library.
   *
   * @return string
   *   The file URI of the file the library definition resides in.
   */
  protected function getFileUri($id) {
    $filename = $id . '.' . $this->serializer->getFileExtension();
    return "library-definitions://$filename";
  }

  /**
   * Returns the library class for a library definition.
   *
   * @param string $id
   *   The ID of the external library.
   * @param array $definition
   *   The library definition array parsed from the definition JSON file.
   *
   * @return string|\Drupal\libraries\ExternalLibrary\LibraryInterface
   *   The library class.
   *
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryClassNotFoundException
   */
  protected function getClass($id, array $definition) {
    // @todo Reconsider
    if (!isset($definition['class'])) {
      throw new LibraryClassNotFoundException($id);
    }
    // @todo Make sure the class exists.
    return $definition['class'];
  }

}
