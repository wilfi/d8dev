<?php
namespace Drupal\wil_url\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WilRedirectSubscriber implements EventSubscriberInterface {

  public function checkForRedirection(GetResponseEvent $event) {

    $baseUrl = $event->getRequest()->getBaseUrl();
    $ser = $event->getRequest()->server;
  $re = $ser->get('REQUEST_URI');

  if($re !== '/home') {
    $event->setResponse(new RedirectResponse($baseUrl.'/home'));
  }
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForRedirection');
    return $events;
  }

}
