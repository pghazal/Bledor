<?php

namespace PG\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use PG\PlatformBundle\Email\OperationMailer;
use PG\PlatformBundle\Entity\Operation;

class OperationCreationListener
{
  /**
   * @var OperationMailer
   */
  private $operationMailer;

  public function __construct(OperationMailer $operationMailer)
  {
    $this->operationMailer = $operationMailer;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    // On ne veut envoyer un email que pour les entités OperationMailer
    if (!$entity instanceof OperationMailer) {
      return;
    }

    $this->operationMailer->sendNewNotification($entity);
  }
}
