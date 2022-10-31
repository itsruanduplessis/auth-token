<?php

namespace Drupal\auth_token\Services;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\Entity\User;

/**
 * @package Drupal\ab_core\Services
 *
 * Provides methods that generates and sets auth tokens.
 */
class AuthTokenService {
  /**
   * Entity type manager.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Sets auth tokens for multiple users at a time.
   *
   * @param array $userIds
   *   The user id.
   */
  public function setAuthToken(array $userIds): void {
    $users = $this->entityTypeManager
      ->getStorage('user')
      ->loadMultiple($userIds);

    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    foreach ($users as $user) {
      $token = $this->generateAuthToken(32, $keyspace);
      $user->set('field_auth_token', $token);
      $user->save();
    }
  }

  /**
   * Generates a unique auth token.
   *
   * @param int $length
   *   The amount of characters to generate.
   * @param string $keyspace
   *   Token data source.
   */
  private function generateAuthToken(int $length, string $keyspace): string {
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;

    for ($i = 0; $i < $length; ++$i) {
      $pieces[] = $keyspace[random_int(0, $max)];
    }

    return implode('', $pieces);
  }

}
