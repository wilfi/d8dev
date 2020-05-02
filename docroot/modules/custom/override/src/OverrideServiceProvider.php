<?php

namespace Drupal\override;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

class OverrideServiceProvider extends ServiceProviderBase implements ServiceProviderInterface
{

  public function alter(ContainerBuilder $container)
  {
    $definition = $container->getDefinition('link_generator');
    $definition->setClass('Drupal\override\MyLinkGenerator\MyLinkGenerator');
  }
}
