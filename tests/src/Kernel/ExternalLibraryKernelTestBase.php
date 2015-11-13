<?php

/**
 * @file
 * Contains \Drupal\Tests\libraries\Kernel\KernelTestBase.
 */

namespace Drupal\Tests\libraries\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Provides an improved version of the core kernel test base class.
 */
abstract class ExternalLibraryKernelTestBase extends KernelTestBase {

  /**
   * The external library registry.
   *
   * @var \Drupal\libraries\ExternalLibrary\Registry\ExternalLibraryRegistryInterface
   */
  protected $externalLibraryRegistry;

  /**
   * The absolute path to the Libraries API module.
   *
   * @var string
   */
  protected $modulePath;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->externalLibraryRegistry = $this->container->get('libraries.registry');

    /** @var \Drupal\Core\Extension\ModuleHandlerInterface $module_handler */
    $module_handler = $this->container->get('module_handler');
    $this->modulePath = $module_handler->getModule('libraries')->getPath();

    $this->installConfig('libraries');
    /** @var \Drupal\Core\Config\ConfigFactoryInterface $config_factory */
    $config_factory = $this->container->get('config.factory');
    $config_factory->getEditable('libraries.settings')
      ->set('library_definitions.local.path', "{$this->modulePath}/tests/library_definitions")
      ->save();
  }

}
