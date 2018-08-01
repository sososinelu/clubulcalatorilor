<?php

namespace Drupal\clubulcalatorilor_flights_importer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Admin-facing form for the s120 importers
 */
class FlightImportersForm extends FormBase {
  private $csv_file;
  private $csv_ip_firm_file;
  private $csv_ip_rpb_file;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ppf_s120_importers_form';
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
    $form['options'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Select which S120 file you want to import'),
    ];

    // Options
    $csv_type = ['emp' => 'Employer', 'or' => 'Official receivers', 'ip' => 'Insolvency practitioners'];

    $form['options']['csv_type'] = array(
      '#type' => 'select',
      '#title' => t('S120 CSV type'),
      '#options' => $csv_type,
      '#required' => TRUE
    );

    $form['main_container'] = array(
      '#markup' => '<div class="file-container hidden">'
    );

    $form['file'] = [
      '#type' => 'file',
      '#title' => 'CSV file upload',
      '#description' => t(''),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#suffix' => '</div>',
    ];

    $form['ip_container'] = array(
      '#markup' => '<div class="ip-container hidden">'
    );

    // Insolvency practitioner firm details
    $form['ip_firm_file'] = [
      '#type' => 'file',
      '#title' => 'Insolvency practitioners firm details CSV file upload',
      '#description' => t('CSV format: No headers. Fields in this order:  id, firm_name, firm_address_1, firm_address_2, firm_address_3, firm_address_4, firm_address_5, firm _postcode, firm_dx_no, firm_dx_exchange, firm_telephone_no, firm_fax_no'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    // Insolvency practitioner recognised professional body details
    $form['ip_rpb_file'] = [
      '#type' => 'file',
      '#title' => 'Insolvency practitioners recognised professional body CSV file upload',
      '#description' => t('CSV format: first row headers. Fields in this order: id, code, description, display_text'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#suffix' => '</div>',
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
    $csv_type = $form_state->getValue(['options', 'csv_type']);
    $this->csv_file = file_save_upload ('file', $form['file']['#upload_validators']);

    if (empty($csv_type)) {
      $form_state->setErrorByName('csv_type', $this->t('Please select an S120 CSV type.'));
    }

    switch ($csv_type) {
      case 'emp':
        if (empty($this->csv_file)) {
          $form_state->setErrorByName('file', $this->t('Please select an S120 Employer CSV file.'));
        }
        break;
      case 'or':
        if (empty($this->csv_file)) {
          $form_state->setErrorByName('file', $this->t('Please select an S120 Official receiver CSV file.'));
        }
        break;
      case 'ip':
        $this->csv_ip_firm_file = file_save_upload ('ip_firm_file', $form['ip_firm_file']['#upload_validators']);
        $this->csv_ip_rpb_file = file_save_upload ('ip_rpb_file', $form['ip_rpb_file']['#upload_validators']);

        if (empty($this->csv_file)) {
          $form_state->setErrorByName('file', $this->t('Please select an S120 Insolvency practitioner CSV file.'));
        }
        if (empty($this->csv_ip_firm_file)) {
          $form_state->setErrorByName('ip_firm_file', $this->t('Please select an S120 Insolvency practitioner firm details CSV file.'));
        }
        if (empty($this->csv_ip_rpb_file)) {
          $form_state->setErrorByName('ip_rpb_file', $this->t('Please select an S120 Insolvency practitioner recognised professional body CSV file.'));
        }
        break;
      default:
        $form_state->setErrorByName('csv_type', $this->t('Please select an S120 CSV type.'));
        break;
    }
  }

  /**
   * {@inheritdoc}
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $csv_type = $form_state->getValue(['options', 'csv_type']);
    $files = [];
    $files[] = $this->csv_file;

    if ($csv_type == 'ip') {
      $files[] = $this->csv_ip_firm_file;
      $files[] = $this->csv_ip_rpb_file;
    }

    if ($created = PpfS120Importers::processUpload($files, $csv_type)) {
      drupal_set_message(t('Successfully imported @count CSV rows.', ['@count' => count($created)]));
    }
    else {
      drupal_set_message(t('No CSV rows imported.'));
    }

    $form_state->setRedirectUrl(new Url('ppf_s120.importers'));
  }

}
