<?php

namespace Drupal\generate_timestamp;

use Drupal\Core\Cache\Cache;

/**
 * Class UserTimestampGenerator.
 */
class UserTimestampGenerator {

  /**
   * Constructs a new UserTimestampGenerator object.
   */
  public function __construct() {

  }

  /**
   * Generate a timestamp.
   */
  public function generateUserTimestamp() {
    // Invalidate the tag.
    Cache::invalidateTags(['timestamp']);
    return [
      '#markup' => time(),
    ];
  }

}
