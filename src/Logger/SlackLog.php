<?php

namespace Drupal\slack_log\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\slack_log\SlackMessageFormatter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * SlackLogger controller.
 */
class SlackLog implements LoggerInterface {
    use RfcLoggerTrait;

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = []) {

        $config = \Drupal::config('slack.settings');
        $channel = $config->get('slack_channel');
        $username = $config->get('slack_username');
        $configSlackLog = \Drupal::config('slack_log.settings');

        // built in a severity check.
        $severity = $configSlackLog->get('severity');


        $message = new SlackMessageFormatter($message);

      $response = \Drupal::service('slack.slack_service')->sendMessage($message, $channel, $username);

      if ($response && RedirectResponse::HTTP_OK == $response->getStatusCode()) {
          drupal_set_message(t('Message was successfully sent!'));
      }
      else {
          drupal_set_message(t('Please check log messages for further details'), 'warning');
      }
    }
}
