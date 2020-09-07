<?php
namespace Drupal\wil_url\StackMiddleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
* Performs a custom task.
*/
class WilMiddle implements HttpKernelInterface {

/**
* The wrapped HTTP kernel.
*
* @var \Symfony\Component\HttpKernel\HttpKernelInterface
*/
protected $httpKernel;

/**
* Creates a HTTP middleware handler.
*
* @param \Symfony\Component\HttpKernel\HttpKernelInterface $kernel
*   The HTTP kernel.
*/
public function __construct(HttpKernelInterface $kernel) {
$this->httpKernel = $kernel;
}

/**
* {@inheritdoc}
*/
public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
// Custom logic goes here.

//  $wil = $request;
//  $ser = $request->server;
//  $re = $ser->get('REDIRECT_URL');
//  $se = $ser->set('REDIRECT_URL', '/home');
//  $we = $ser->get('REDIRECT_URL');
 // $current_path = \Drupal::service('path.current')->getPath();
return $this->httpKernel->handle($request, $type, $catch);
}
}
