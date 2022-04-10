<?php

namespace Drupal\generate_timestamp;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Class UserTimestampGenerator.
 */
class UserTimestampGenerator implements TrustedCallbackInterface {

  /**
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private DateFormatterInterface $date_formatter;

  /**
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  private TimeInterface $time;

  /**
   * Constructs a new UserTimestampGenerator object.
   */
  public function __construct(DateFormatterInterface $date_formatter, TimeInterface $time) {
    $this->date_formatter = $date_formatter;
    $this->time = $time;
  }

  /**
   * Generate a timestamp.
   */
  public function generateUserTimestamp() {
    $current_time = $this->time->getCurrentTime();
    $date_today = $this->date_formatter->format($current_time, 'custom', 'r');
    // Invalidate the tag.
    Cache::invalidateTags(['timestamp']);
    return [
      '#markup' => $date_today,
    ];
  }

  public static function trustedCallbacks() {
    return ['generateUserTimestamp'];
  }

}
