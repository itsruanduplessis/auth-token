<?php

namespace Drupal\auth_token\Batch;

/**
 * Batch processing file.
 */
class UserAuthTokenBatch {

  /**
   * Creates authentication tokens for all users in the site.
   */
  public static function authUsers($uids, &$context): void {
    $context['message'] = t('Add assets to sync queue...');

    if (empty($context['sandbox'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['current_id'] = 0;
    }

    // Call auth token service which generates the token and updates the user profiles.
    \Drupal::service('auth_token.generate_token')->setAuthToken($uids);

    $context['sandbox']['progress']++;
    $context['sandbox']['current_id'] = max($uids);
    $context['message'] = 'Creating auth tokens';
  }

  /**
   * Batch complete.
   */
  public static function completeBatch($success, $results, $operations): void {
    if ($success) {
      $message = 'Auth token batch completed.';
    }
    else {
      $message = t('Finished with an error.');
    }

    $messenger = \Drupal::messenger();
    $messenger->addMessage($message);
  }

}
