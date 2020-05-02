<?php

namespace Drupal\override\MyLinkGenerator;

use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGenerator;
use Drupal\Core\Utility\LinkGeneratorInterface;

class MyLinkGenerator extends LinkGenerator implements LinkGeneratorInterface
{

  public function generate($text, Url $url)
  {
    $url->setAbsolute(TRUE);
    $generate_link = parent::generate($text, $url);
    return $generate_link;
  }
}
