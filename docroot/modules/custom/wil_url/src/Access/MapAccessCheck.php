<?php

namespace Drupal\wil_url\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Determines access to for block add pages.
 */
class MapAccessCheck implements AccessInterface {

  /**
   * Checks access to the block add page for the block type.
   */
  public function access($block_content_type, AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, "create $block_content_type block content");
  }

}
