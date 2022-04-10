<?php

namespace Drupal\generate_timestamp\Plugin\Block;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'StaticTimestamp' block.
 *
 * @Block(
 *  id = "static_timestamp",
 *  admin_label = @Translation("Static timestamp"),
 * )
 */
class StaticTimestamp extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Date Time Formatter.
   *
   * @var Drupal\Core\Datetime\DateFormatterInterface
   */
  private $dateFormatter;

  /**
   * Drupal Time Service.
   *
   * @var Drupal\Component\Datetime\TimeInterface
   */
  private $timeService;

  /**
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The Date Time Formatter Interface.
   * @param \Drupal\Component\Datetime\TimeInterface $time_service
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter, TimeInterface $time_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $date_formatter;
    $this->timeService = $time_service;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('date.formatter'), $container->get('datetime.time'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_time = $this->timeService->getCurrentTime();
    $date_today = $this->dateFormatter->format($current_time, 'custom', 'r');
    $build = [];
    $build['static_timestamp']['#markup'] = $this->t('Timestamp that this block was generated @time', ['@time' => $date_today]);
    return $build;
  }

}
