<?php
/**
 * Provides the footer building block
 *
 * @Block(
 *   id = "footer_building_block",
 *   admin_label = @Translation("Footer building block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class FooterBuildingBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    return array(
      '#theme' => 'footer_building_block_template',
      '#vars' => array(
      ),
      '#cache' => array('max-age' => 0),
    );
  }
}
