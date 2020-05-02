<?php

namespace Drupal\bla;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

class BlaServiceProvider extends ServiceProviderBase implements ServiceProviderInterface
{

  public function alter(ContainerBuilder $container)
  {
    $definition = $container->getDefinition('override.calc_fibo');
    $definition->setClass('Drupal\bla\BlaCalcFibo');
  }
}
