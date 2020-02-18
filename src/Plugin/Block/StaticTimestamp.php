<?php

namespace Drupal\generate_timestamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'StaticTimestamp' block.
 *
 * @Block(
 *  id = "static_timestamp",
 *  admin_label = @Translation("Static timestamp"),
 * )
 */
class StaticTimestamp extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['static_timestamp']['#markup'] = $this->t('Timestamp that this block was generated @time', ['@time' => time()]);
    return $build;
  }

}
