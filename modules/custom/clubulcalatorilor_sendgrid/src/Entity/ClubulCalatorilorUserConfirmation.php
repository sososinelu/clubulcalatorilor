<?php

namespace Drupal\clubulcalatorilor_sendgrid\Entity;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the Clubul Calatorilor user confirmation entity.
 *
 * @ingroup cc_user_conf_entity
 *
 * @ContentEntityType(
 *   id = "cc_user_conf_entity",
 *   label = @Translation("Clubul Calatorilor user confirmation entity."),
 *   base_table = "cc_user_conf_entity",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 * )
 */
class ClubulCalatorilorUserConfirmation extends ContentEntityBase implements ContentEntityInterface
{
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Id'))
      ->setReadOnly(TRUE);

    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email'))
      ->setSetting('max_length', 128);

    $fields['token'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Token'))
      ->setSetting('max_length', 128);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));

    return $fields;
  }

  /**
   * @param $token
   * @return object
   */
  public static function getUserByToken($token) {
    try {
      $user_details = \Drupal::entityTypeManager()->getStorage('cc_user_conf_entity')->loadByProperties(['token' => $token]);
    } catch (InvalidPluginDefinitionException $e) {
    }

    return reset($user_details);
  }

    /**
   * @param $token
   * @return object
   */
  public static function getUserByEmail($email) {
    try {
      $user_details = \Drupal::entityTypeManager()->getStorage('cc_user_conf_entity')->loadByProperties(['email' => $email]);
    } catch (InvalidPluginDefinitionException $e) {
    }

    return reset($user_details);
  }
}
