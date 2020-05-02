<?php


namespace Drupal\bla;

use Drupal\override\FibonacciService;

class BlaCalcFibo extends FibonacciService
{

  /**
   * @param $numberOfNumbers
   * @return array|void
   */
  public function calcSomeFibos($numberOfNumbers) {
    $series = parent::calcSomeFibos($numberOfNumbers);
    if(count($series) == $numberOfNumbers) {
      array_push($series, 'end');
    }
    return $series;
  }
}
