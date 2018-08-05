<?php

namespace Drupal\clubulcalatorilor_flights_importer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\clubulcalatorilor_flights_importer\Controller\ClubulCalatorilorFlightsImporter;

/**
 * Admin-facing form for the s120 importers
 */
class FlightImportersForm extends FormBase {
  private $csv_file;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cc_flights_importer_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#tree'] = TRUE;

    $form['main_container'] = array(
      '#markup' => '<div class="file-container"><h2>Select a flights CSV to import</h2'
    );

    $form['file'] = [
      '#type' => 'file',
      '#title' => 'CSV file upload',
      '#description' => t(''),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ]
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#button_type' => 'primary',
    ];


    $form['#theme'] = 'system_config_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate options.
    $this->csv_file = file_save_upload ('file', $form['file']['#upload_validators']);

    if (empty($this->csv_file)) {
      $form_state->setErrorByName('file', $this->t('Please select a CSV file.'));
    }

  }

  /**
   * {@inheritdoc}
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if ($flights_data = ClubulCalatorilorFlightsImporter::processUpload($this->csv_file)) {
      drupal_set_message(t('Successfully processed @count CSV rows.', ['@count' => $flights_data['count']]));
    }
    else {
      drupal_set_message(t('No CSV rows imported.'));
    }


    //$form_state->setRedirectUrl(new Url('clubulcalatorilor_flights_importer.importers'));
    echo '<a href="/">Back to homepage</a>';
    echo "<br/>";
    echo "<br/>";
    echo htmlspecialchars($flights_data['flights_template']);
    echo "<br/>";
    echo "<br/>";
    echo '<a href="/">Back to homepage</a>';
    exit;
  }

}
