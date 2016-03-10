<?php

/**
 * @file
 * API documentation for Social Links.
 */

/**
 * Define social link providers and alter defaults.
 *
 * @return array
 */
function hook_social_links_alter() {
  // Add a linkedin provider, using the default popup callback
  return array(
    'linkedin' => array(
      'callback' => 'social_links_provider_popup',
    ),
  );
}
