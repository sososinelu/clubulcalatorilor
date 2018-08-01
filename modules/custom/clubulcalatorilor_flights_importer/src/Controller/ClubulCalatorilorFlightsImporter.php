<?php

namespace Drupal\ppf_s120_iclubulcalatorilor_flights_importermporters\Controller;
use Drupal\Core\Entity\EntityStorageException;

/**
 * Controller functions for s120 importers functionality.
 */
class ClubulCalatorilorFlightsImporter {

  /**
   * Processes an uploaded CSV file
   * @param array $files
   * @param $csv_type
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Exception
   */
  public static function processUpload(array $files, $csv_type) {
    $created = [];

    $removed = self::removeEntities($csv_type);

    if($removed) {
      foreach ($files as $key => $file) {
        $count = 0;
        $handle = fopen($file[0]->destination, 'r');

        while ($row = fgetcsv($handle)) {
          // Skip CSV headers
          if($count == 0) {
            $count++;
            continue;
          }
          // Skip empty rows
          if (is_null($row[0]) || $row[0] == '') {
            continue;
          }

          if($csv_type == 'emp') {
            $values = self::prepareEmployerRow($row);
            if ($values) {
              if ($uid = self::createEmployer($values)) {
                $created[$uid] = $values;
              }
            }
          }elseif($csv_type == 'or') {
            $values = self::prepareOfficialReceiverRow($row);
            if ($values) {
              if ($uid = self::createOfficialReceiver($values)) {
                $created[$uid] = $values;
              }
            }
          }elseif($csv_type == 'ip') {
            if($key == 0) {
              $values = self::prepareInsolvencyPractitionerRow($row);
              if ($values) {
                if ($uid = self::createInsolvencyPractitioner($values)) {
                  $created[$uid] = $values;
                }
              }
            }elseif($key == 1) {
              self::addInsolvencyPractitionerFirmData($row);
            }elseif($key == 2) {
              self::addInsolvencyPractitionerRecognisedProfessionalBodyData($row);
            }

          }
        }

        fclose($handle);
      }
    }

    return $created;
  }

  /**
   * Prepares employer data from an upload row
   * @param array $row
   * @return array
   */
  public static function prepareEmployerRow(array $row) {

    return array(
      'employer_name' => ($row[0] == 'NULL' ? NULL : $row[0]),
      'crn' => ($row[1] == 'NULL' ? NULL : $row[1]),
      'address_1' => ($row[2] == 'NULL' ? NULL : $row[2]),
      'address_2' => ($row[3] == 'NULL' ? NULL : $row[3]),
      'address_3' => ($row[4] == 'NULL' ? NULL : $row[4]),
      'address_4' => ($row[5] == 'NULL' ? NULL : $row[5]),
      'address_5' => ($row[6] == 'NULL' ? NULL : $row[6]),
      'postcode' => ($row[7] == 'NULL' ? NULL : $row[7]),
      'country' => ($row[8] == 'NULL' ? NULL : $row[8]),
      'ppf_employer_ref' => ($row[9] == 'NULL' ? NULL : $row[9]),
    );
  }

  /**
   * Prepares official receiver data from an upload row
   * @param array $row
   * @return array
   */
  public static function prepareOfficialReceiverRow(array $row) {

    return array(
      'office_name' => ($row[0] == 'NULL' ? NULL : $row[0]),
      'or_address_1' => ($row[1] == 'NULL' ? NULL : $row[1]),
      'or_address_2' => ($row[2] == 'NULL' ? NULL : $row[2]),
      'or_address_3' => ($row[3] == 'NULL' ? NULL : $row[3]),
      'or_address_4' => ($row[4] == 'NULL' ? NULL : $row[4]),
      'or_address_5' => ($row[5] == 'NULL' ? NULL : $row[5]),
      'or_postcode' => ($row[6] == 'NULL' ? NULL : $row[6]),
      'or_name' => ($row[7] == 'NULL' ? NULL : $row[7]),
      'dx' =>($row[8] == 'NULL' ? NULL : $row[8]),
      'or_email_address' => ($row[9] == 'NULL' ? NULL : $row[9]),
      'or_telephone_no' => ($row[10] == 'NULL' ? NULL : $row[10]),
      'or_fax_no' => ($row[11] == 'NULL' ? NULL : $row[11])
    );
}

  /**
   * Prepares insolvency practitioner data from an upload row
   * @param array $row
   * @return array
   */
  public static function prepareInsolvencyPractitionerRow(array $row) {

    return array(
      'ip_firm_id' => ($row[0] == 'NULL' ? NULL : $row[0]),
      'ip_rpb_id' => ($row[1] == 'NULL' ? NULL : $row[1]),
      'ip_number' => ($row[2] == 'NULL' ? NULL : $row[2]),
      'ip_title' => ($row[3] == 'NULL' ? NULL : $row[3]),
      'ip_first_name' => ($row[4] == 'NULL' ? NULL : $row[4]),
      'ip_last_name' => ($row[5] == 'NULL' ? NULL : $row[5]),
      'ip_email_address' => ($row[6] == 'NULL' ? NULL : $row[6]),
    );
  }

  /**
   * Creates a new employer from prepared values.
   * @param $values
   * @return bool
   */
  private static function createEmployer($values) {
    $tempstore = \Drupal::service('user.private_tempstore')->get('ppf_s120_importers');
    $tempstore->set('ppf_s120_importers', TRUE);

    $employer = PpfS120EmployerEntity::create($values);

    try {
      if ($employer->save()) {
        $tempstore->delete('ppf_s120_importers');
        return $employer->id();
      }
    }
    catch (EntityStorageException $e) {
      drupal_set_message(t('Could not create employer row (name: %employer_name); exception: %e', [
        '%e' => $e->getMessage(),
        '%employer_name' => $values['employer_name'],
      ]), 'error');
    }
    $tempstore->delete('ppf_s120_importers');
    return FALSE;
  }

  /**
   * Creates a new official receiver from prepared values.
   * @param $values
   * @return bool
   */
  private static function createOfficialReceiver($values) {
    $tempstore = \Drupal::service('user.private_tempstore')->get('ppf_s120_importers');
    $tempstore->set('ppf_s120_importers', TRUE);

    $official_receiver = PpfS120OfficialReceiverEntity::create($values);

    try {
      if ($official_receiver->save()) {
        $tempstore->delete('ppf_s120_importers');
        return $official_receiver->id();
      }
    }
    catch (EntityStorageException $e) {
      drupal_set_message(t('Could not create official receiver row (office name: %office_name); exception: %e', [
        '%e' => $e->getMessage(),
        '%office_name' => $values['office_name'],
      ]), 'error');
    }
    $tempstore->delete('ppf_s120_importers');
    return FALSE;
  }

  /**
   * Creates a new insolvency practitioner from prepared values.
   * @param $values
   * @return bool
   */
  private static function createInsolvencyPractitioner($values) {
    $tempstore = \Drupal::service('user.private_tempstore')->get('ppf_s120_importers');
    $tempstore->set('ppf_s120_importers', TRUE);

    $insolvency_practitioner = PpfS120IPEntity::create($values);

    try {
      if ($insolvency_practitioner->save()) {
        $tempstore->delete('ppf_s120_importers');
        return $insolvency_practitioner->id();
      }
    }
    catch (EntityStorageException $e) {
      drupal_set_message(t('Could not create insolvency practitioner row (ip number: %ip_number); exception: %e', [
        '%e' => $e->getMessage(),
        '%ip_number' => $values['ip_number'],
      ]), 'error');
    }
    $tempstore->delete('ppf_s120_importers');
    return FALSE;
  }

  /**
   * Update insolvency practitioner with firm data
   * @param $values
   * @return bool
   * @throws \Exception
   */
  private static function addInsolvencyPractitionerFirmData($values) {
    if($ip_values = PpfS120IPEntity::getIPsByProperties('ip_firm_id', $values[0])) {
      foreach ($ip_values as $ip_value) {
        $ip_value->set('ip_firm_name', ($values[1] == 'NULL' ? NULL : $values[1]));
        $ip_value->set('ip_firm_address_1', ($values[2] == 'NULL' ? NULL : $values[2]));
        $ip_value->set('ip_firm_address_2', ($values[3] == 'NULL' ? NULL : $values[3]));
        $ip_value->set('ip_firm_address_3', ($values[4] == 'NULL' ? NULL : $values[4]));
        $ip_value->set('ip_firm_address_4', ($values[5] == 'NULL' ? NULL : $values[5]));
        $ip_value->set('ip_firm_address_5', ($values[6] == 'NULL' ? NULL : $values[6]));
        $ip_value->set('ip_firm_postcode', ($values[7] == 'NULL' ? NULL : $values[7]));

        try {
          $ip_value->save();
        } catch (EntityStorageException $e) {
          throw new \Exception($e->getMessage());
        }
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Update insolvency practitioner with recognised professional body data
   * @param $values
   * @return bool
   * @throws \Exception
   */
  private static function addInsolvencyPractitionerRecognisedProfessionalBodyData($values) {
    if($ip_values = PpfS120IPEntity::getIPsByProperties('ip_rpb_id', $values[0])) {
      foreach ($ip_values as $ip_value) {
        $ip_value->set('ip_recognised_professional_body', ($values[1] == 'NULL' ? NULL : $values[1]));

        try {
          $ip_value->save();
        } catch (EntityStorageException $e) {
          throw new \Exception($e->getMessage());
        }
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Remove entities before importing new data
   * @param $csv_type
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  private static function removeEntities($csv_type){

    switch ($csv_type) {
      case 'emp':
        $entity = 'ppf_s120_employer_entity';
        break;
      case 'or':
        $entity = 'ppf_s120_or_entity';
        break;
      case 'ip':
        $entity = 'ppf_s120_ip_entity';
        break;
      default:
        return FALSE;
        break;
    }

    $values = \Drupal::entityTypeManager()->getStorage($entity)->load(1);

    if (!empty($values)) {
      \Drupal::database()->truncate($entity)->execute();
      return TRUE;
    }

    return TRUE;
  }
}
