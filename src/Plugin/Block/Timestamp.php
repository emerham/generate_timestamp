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
 * )
 */
class Timestamp extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $accountProxy;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AccountProxyInterface $accountProxy
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->accountProxy = $accountProxy;
  }


  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
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
//    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
