<?php
/**
 * Provides the homepage how it works block
 *
 * @Block(
 *   id = "homepage_how_it_works_block",
 *   admin_label = @Translation("Homepage how it works block"),
 * )
 */

namespace Drupal\clubulcalatorilor\Plugin\Block;
use Drupal\Core\Block\BlockBase;

class HomepageHowItWorksBlock extends BlockBase
{
  /**
   * @return array
   */
  public function build()
  {
    if($node = \Drupal::routeMatch()->getParameter('node')) {

      $sections = [];
      $title = ($node->field_how_it_works_title ? $node->field_how_it_works_title->value : '');
      // titles
      $sections['section_1_title'] = ($node->field_hiw_section_1_title ? $node->field_hiw_section_1_title->value : '');
      $sections['section_2_title'] = ($node->field_hiw_section_2_title ? $node->field_hiw_section_2_title->value : '');
      $sections['section_3_title'] = ($node->field_hiw_section_3_title ? $node->field_hiw_section_3_title->value : '');
      $sections['section_4_title'] = ($node->field_hiw_section_4_title ? $node->field_hiw_section_4_title->value : '');
      // images
      $sections['section_1_image'] = ($node->field_hiw_section_1_img ? $node->field_hiw_section_1_img : '');

      //echo '<pre>';kint($node->field_hiw_section_1_img);echo '</pre>';exit;

      $sections['section_2_image'] = ($node->field_hiw_section_2_img ? $node->field_hiw_section_2_img : '');
      $sections['section_3_image'] = ($node->field_hiw_section_3_img ? $node->field_hiw_section_3_img : '');
      $sections['section_4_image'] = ($node->field_hiw_section_4_img ? $node->field_hiw_section_4_img : '');
      //text
      $sections['section_1_text'] = ($node->field_hiw_section_1_text ? $node->field_hiw_section_1_text->value : '');
      $sections['section_2_text'] = ($node->field_hiw_section_2_text ? $node->field_hiw_section_2_text->value : '');
      $sections['section_3_text'] = ($node->field_hiw_section_3_text ? $node->field_hiw_section_3_text->value : '');
      $sections['section_4_text'] = ($node->field_hiw_section_4_text ? $node->field_hiw_section_4_text->value : '');

      return array(
        '#theme' => 'homepage_how_it_works_template',
        '#vars' => array(
          'title' => $title,
          'sections' => $sections,
        ),
        '#cache' => array('max-age' => 0),
      );
    }

    return array();
  }
}
