<?php

/**
 * @file
 * Contains \Drupal\libraries\ExternalLibrary\Registry\LibraryRegistry.
 */

namespace Drupal\libraries\ExternalLibrary\Registry;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Component\Serialization\SerializationInterface;
use Drupal\libraries\ExternalLibrary\Exception\LibraryDefinitionNotFoundException;
use Drupal\libraries\ExternalLibrary\Exception\LibraryTypeNotFoundException;
use Drupal\libraries\ExternalLibrary\LibraryType\LibraryCreationListenerInterface;

/**
 * Provides an implementation of a registry of external libraries.
 *
 * @todo Consider moving parts of this logic into LibraryManager.
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
   * The library type manager.
   *
   * @var \Drupal\Component\Plugin\Factory\FactoryInterface
   */
  protected $libraryTypeFactory;

  /**
   * Constructs a registry of external libraries.
   *
   * @param \Drupal\Component\Serialization\SerializationInterface $serializer
   *   The serializer for the library definition files.
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $library_type_factory
   *   The library type manager.
   */
  public function __construct(
    SerializationInterface $serializer,
    FactoryInterface $library_type_factory
  ) {
    $this->serializer = $serializer;
    $this->libraryTypeFactory = $library_type_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getLibrary($id) {
    $library_type = $this->getLibraryType($id);

    $class = $library_type->getLibraryClass();
    // @todo Make sure that the library class implements the correct interface.
    $library = $class::create($id, $this->getDefinition($id));

    if ($library_type instanceof LibraryCreationListenerInterface) {
      $library_type->onLibraryCreate($library);
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
   *
   * @throws \Drupal\libraries\ExternalLibrary\Exception\LibraryDefinitionNotFoundException
   */
  protected function getDefinition($id) {
    if (!$this->hasDefinition($id)) {
      throw new LibraryDefinitionNotFoundException($id);
    }
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
   * {@inheritdoc}
   */
  public function getLibraryType($id) {
    $definition = $this->getDefinition($id);
    // @todo Validate that the type is a string.
    if (!isset($definition['type'])) {
      throw new LibraryTypeNotFoundException($id);
    }
    return  $this->libraryTypeFactory->createInstance($definition['type']);
  }

}
