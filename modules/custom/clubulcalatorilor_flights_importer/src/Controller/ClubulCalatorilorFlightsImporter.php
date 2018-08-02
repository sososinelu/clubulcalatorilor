<?php

namespace Drupal\clubulcalatorilor_flights_importer\Controller;

/**
 * Controller functions for Clubul Calatorilot flights importer functionality.
 */
class ClubulCalatorilorFlightsImporter {

  /**
   * Processes an uploaded CSV file
   * @param array $file
   * @return array
   */
  public static function processUpload($file)
  {
    $flights_data = [];

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

      $values = self::prepareFlightRow($row);
      if ($values) {
        $flights_data[] = $values;
      }
    }

    fclose($handle);

    $flights_template = self::processTemplate($flights_data);

    return ['count' => $count, 'flights_template' => $flights_template];
  }

  /**
   * Prepares flight data from an upload row
   * @param array $row
   * @return array
   */
  private static function prepareFlightRow(array $row)
   {

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

  private static function processTemplate($flights_data)
  {
    $template_data = array (
      '#theme' => 'flights_template',
      '#vars' => array(
        "flights" => $flights_data
      )
    );

    $flights_template =  \Drupal::service('renderer')->render($template_data);

    return $flights_template;
  }

}
