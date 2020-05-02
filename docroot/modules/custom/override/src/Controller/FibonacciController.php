<?php

namespace Drupal\override\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class FibonacciController.
 */
class FibonacciController extends ControllerBase {

  /**
   * Build.
   *
   * @return array
   *   Return series.
   */
  public function build($num) {
    $series = \Drupal::service('override.calc_fibo')->calcSomeFibos($num);
    return [
      '#theme' => 'fib_template',
      '#series' => $series,
    ];
  }
}
