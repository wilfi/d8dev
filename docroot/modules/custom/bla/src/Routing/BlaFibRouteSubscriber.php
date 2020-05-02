<?php

namespace Drupal\bla\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class BlaFinRouteSubscriber.
 *
 * Listens to the dynamic route events.
 */
class BlaFibRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $routes = [
      $collection->get('override.fibonacci_controller'),
    ];
    foreach ($routes as $route) {
      $route->setPath('/bla/fib/{num}');
      $route->setDefault('num', 5);
    }

    $user_pages = [
      $collection->get('user.page'),
    ];
    foreach ($user_pages as $user_route) {
      $user_route->setPath('/bla');
    }

    $user_logins = [
      $collection->get('user.login'),
    ];
    foreach ($user_logins as $user_login) {
      $user_login->setPath('/bla/login');
    }

    $routes = [
      $collection->get('system.site_information_settings'),
    ];
    foreach ($routes as $route) {
      if ($route) {
        // Extend core site config form.
        $route->setDefault('_form', 'Drupal\bla\Form\SiteSettingsFormExtended');
      }
    }

  }
}
