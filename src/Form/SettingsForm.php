<?php

/**
 * @file
 * Contains Drupal\slack\Form\SettingsForm.
 * Configures administrative settings for slack.
 */

namespace Drupal\slack_log\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Logger\RfcLogLevel;

/**
 * Class SettingsForm.
 *
 * @package Drupal\slack\Form
 *
 * @ingroup slack
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'slack_log_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['slack_log.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {


      $default_checkbox = [];
      $config = $this->config('slack_log.settings');
      $levels = RfcLogLevel::getLevels();
      $severity = $config->get('severity');
      $enable_default_value = !empty($config->get('enable')) ? $config->get('enable') : 0;
      foreach ($severity as $key => $value) {
          if ($value) {
              $default_checkbox[] = $key;
          }
      }
      foreach ($levels as $key => $value) {
          $options[$key] = $value->getUntranslatedString();
      }
      $form['enable'] = [
          '#type' => 'checkbox',
          '#title' => t('Enable Slack Notification?'),
          '#default_value' => $enable_default_value,
      ];
      $form['severity'] = [
          '#type' => 'checkboxes',
          '#title' => t('Severity'),
          '#options' => $options,
          '#required' => TRUE,
          '#default_value' => $default_checkbox,
          '#description' => '<p>' . t('Choose Severity Level') . '</p>',
      ];

      return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('slack_log.settings');
    $config
        ->set('severity', $form_state->getValue('severity'))
        ->set('enable', $form_state->getValue('enable'))
        ->save();
    parent::submitForm($form, $form_state);
  }

}
