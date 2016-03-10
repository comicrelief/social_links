<?php

/**
 * @file
 * Contains \Drupal\social_links\Plugin\Social\SocialLinks.
 */

namespace Drupal\social_links\Plugin\Social;

use \Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Plugin implementation of the 'social_links' field type.
 *
 *
 */
class SocialLinks {

  private $social_links = [];
  private $theme_function = 'social_links_theme';

  public function __construct(&$links, $override = false) {
    $links = [] + $links;

    if (!$override && is_array($links)) {
      $links = $this->getDefaults() + $links;
    }

    $this->registerLinks($links);

  }

  public function registerLinks($links) {
    $module_handler = \Drupal::ModuleHandlerInterface();
    $hook_links = $module_handler->invokeAll('hook_social_links_alter', $links);

    if (!empty($hook_links) {
      $links = $hook_links;
    }

    $this->addLinks($links);

  }

  public function getDefaults() {
    // supply defaults
    return [];
  }

  public function addLinks($links) {
    if (is_array($links)) {
      $this->social_links = $this->social_links + $links;
    }
  }

  public function renderLinks($entity, &$theme_override = '') {
    return array(
      '#markup' => $this->getMarkup($entity),
      '#theme' => $this->getTheme($theme_override),
    );
  }

  public function getMarkup($entity) {
    $links = $this->getLinks();

    // look at nice way to attch js, maybe to theme array
    foreach ($links as $provider => $config) {
      // get entity info
      // check in about svg support/ contrib module support
      // build links array
      // run call backs
    }
    // return links array
  }

  public function getLinks() {
    return $this->social_links;
  }

  public function getTheme(&$override) {
    if (!is_null($override)) {
      $this->theme_function = $override;
      return $override;
    }
    return $this->theme_function;
  }

}
