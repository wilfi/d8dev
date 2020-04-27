<?php


/**
 * @file
 * Hooks related to module.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

use Drupal\node\NodeInterface;

/**
 * API.
 */

/**
 * @param $current_count
 * @param NodeInterface $node
 */
function hook_demo_node_viewcount($current_count, NodeInterface $node)
{
  if ($current_count === 1) {
    \Drupal::messenger()
      ->addMessage(t('This is the first time you have viewed the node %title.', [
        '%title' => $node
          ->label(),
      ]));
  }
}

/**
 * @param $item
 * @return mixed
 */
function hook_demo_list($item) {
  return $item;
}

/**
 * @param $item
 */
function hook_demo_list_alter(&$item) {
}

/**
 * @param $items
 */
function hook_demo_example_alter(&$items) {
}
