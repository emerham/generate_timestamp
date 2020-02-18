<?php

namespace Drupal\generate_timestamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Timestamp' block.
 *
 * @Block(
 *  id = "timestamp",
 *  admin_label = @Translation("Timestamp"),
 *  category = @Translation("Generate Timestamp"),
 * )
 */
class Timestamp extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Account Proxy Interface.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $accountProxy;

  /**
   * Timestamp constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *   Drupal Account Proxy Interface.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AccountProxyInterface $accountProxy
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->accountProxy = $accountProxy;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheMaxAge() {
    return 60;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $user = $this->accountProxy->getAccount();
    $build['timestamp'] = [
      '#lazy_builder' => [
        'timestamp_generator.generator:generateUserTimestamp',
        [],
      ],
      '#create_placeholder' => TRUE,
    ];
    $build['#markup'] = $this->t('The current timestamp is ');
    $build['#cache']['contexts'][] = 'languages';
    $build['#cache']['tags'][] = 'timestamp';
    // $build['#cache']['max-age'] = 0;
    return $build;
  }

}
