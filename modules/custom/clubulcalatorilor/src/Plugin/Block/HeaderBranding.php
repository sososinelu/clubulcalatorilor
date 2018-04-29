<?php
/**
 * Provides the header branding block
 *
 * @Block(
 *   id = "header_branding_block",
 *   admin_label = @Translation("Header branding block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class HeaderBranding extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    $content = '<div class="branding-logo">';
    $content .= '<a href="/" title="Home" rel="home">';
    //$content .= '<img src="/themes/custom/clubulcalatorilor_theme/images/cc_logo_header_blue_white.png" title="Clubul calatorilor logo" alt="Clubul calatorilor logo">';
    $content .= '<img src="/themes/custom/clubulcalatorilor_theme/images/cc_logo_header_blue_pink.png" title="Clubul calatorilor logo" alt="Clubul calatorilor logo">';
    $content .= '</a></div>';

    return array(
      '#type' => 'markup',
      '#markup' => $content,
      '#cache' => array('max-age' => 0),
    );

  }
}
