<?php

namespace Drupal\rh_node\Plugin\RabbitHoleEntityPlugin;

use Drupal\rabbit_hole\Plugin\RabbitHoleEntityPluginBase;

/**
 * Implements rabbit hole behavior for nodes.
 *
 * @RabbitHoleEntityPlugin(
 *  id = "rh_node",
 *  label = @Translation("Node"),
 *  entityType = "node"
 * )
 */
class Node extends RabbitHoleEntityPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFormSubmitHandlerAttachLocations() {
    return [
      ['actions', 'submit', '#submit'],
      ['actions', 'publish', '#submit'],
    ];
  }

}
