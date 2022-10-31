<?php

namespace Drupal\auth_token\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\Entity\User;

/**
 * Event subscriber that handles HTTP login requests.
 */
class AuthTokenEventSubscriber implements EventSubscriberInterface {

  /**
   * Entity type manager Interface.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   For logging purposes.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Event listeners.
   *
   * @return array
   *   An array of event listener definitions.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onRequest', 255];
    return $events;
  }

  /**
   * Listener that handles authenticating.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The event to process.
   */
  public function onRequest(GetResponseEvent $event) {
    // Check if the request contains the "authtoken" parameter.
    $auth_parameter = $event->getRequest()->query->get('authtoken');
    if ($auth_parameter) {

      // Load user that matches the auth token and log them into the site.
      $storage = $this->entityTypeManager->getStorage('user');
      $userId = $storage->getQuery()
        ->condition('field_auth_token', $auth_parameter)
        ->condition('status', '1')
        ->execute();

      if (!empty($userId) && $userId = array_values($userId)) {
        $user = User::load($userId[0]);
        user_login_finalize($user);
      }
    }
  }

}
