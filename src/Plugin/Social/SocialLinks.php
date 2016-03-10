<?php

/**
 * @file
 * Contains \Drupal\social_links\Plugin\Social\SocialLinks.
 */

namespace Drupal\social_links\Plugin\Social;

use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Plugin implementation of the 'social_links' field type.
 *
 *
 */
class SocialLinks {

  private $social_links = [];
  private $theme_function = 'item_list';

  public function __construct(&$links = [], &$override = false) {
    $links = array_merge([], $links);

    if (!$override && is_array($links)) {
      $links = array_merge($this->getDefaults(), $links);
    }

    $this->registerLinks($links);

  }

  public function registerLinks($links) {
    $module_handler = \Drupal::moduleHandler();
    $hook_links = $module_handler->invokeAll('social_links_alter', array($links));

    if (!empty($hook_links)) {
      $links = $hook_links;
    }

    $this->addLinks($links);

  }

  public function getDefaults() {
    return [
      'twitter' => ['callback' => 'social_links_provider_popup'],
      'facebook' => ['callback' => 'social_links_provider_popup'],
      'googleplus' => ['callback' => 'social_links_provider_popup'],
      'email' => ['callback' => 'social_links_provider_email'],
    ];
  }

  public function addLinks($links) {
    if (is_array($links)) {
      $this->social_links = $this->social_links + $links;
    }
  }

  public function renderLinks($entity, &$theme_override = '') {
    return array(
      '#items' => $this->getMarkup($entity),
      '#theme' => $this->getTheme($theme_override),
    );
  }

  public function getMarkup($entity) {
    $markup_array = [];
    $links = $this->getLinks();
    // Make external link
    $url = $entity->toUrl('canonical')->toString();

    // look at nice way to attch js, maybe to theme array
    foreach ($links as $provider => $config) {

      // check in about svg support/ contrib module support

      // Investigate D8 link class or correct way of passing to link theme
      $link = [
        '#theme' => 'link',
        '#path' => $url,
        '#text' => $provider,
        '#options' => [
          'attributes' => ['class' => ['social-link', $provider]],
          'html' => TRUE,
        ],
      ];

      if (isset($config['callback'])) {
        $config['callback']($link, $entity);
      }

      $markup_array[] = $link;
    }

    return $markup_array;
  }

  public function getLinks() {
    return $this->social_links;
  }

  public function getTheme(&$override) {
    if (!empty($override)) {
      $this->theme_function = $override;
      return $override;
    }
    return $this->theme_function;
  }

}
