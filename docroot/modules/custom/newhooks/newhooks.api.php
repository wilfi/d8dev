<?php


/**
 * @file
 * Hooks related to module.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup acquia_spi Acquia Connector SPI module integrations.
 *
 * Module integrations with the Acquia Insight service.
 */

/**
 * Include data to be sent to Acquia Insight as part of the SPI process.
 *
 *  Include custom site information to be sent to the Acquia Insight service
 *  for detailed site analysis. Insight will process this data and alert
 *  appropriately.
 *
 * @return array
 *   An array of custom data keyed by unique identifier.
 *
 *   Required format 'string' => array().
 */
function hook_newhooks_wil($data)
{
  $data['example'] = [
    'result' => TRUE,
    'value' => '9000',
  ];
  return $data;
}

/**
 * Alter hook for userinfo from remote provider.
 *
 * @param mixed $claim_value
 *   The claim value.
 * @param array $context
 *   An context array containing:
 *     - claim: The current claim.
 *     - property_name: The property the claim is mapped to.
 *     - property_type: The property type the claim is mapped to.
 *     - userinfo: The complete userinfo array.
 *     - userinfo_mapping: The complete userinfo mapping.
 */
function hook_newhooks_shalin_alter(&$claim_value, array $context) {
}

function hook_newhooks_list($item) {
  return $item;
}

function hook_newhooks_list_alter(&$item) {
}
