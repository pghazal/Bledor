<?php

namespace PG\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use PG\PlatformBundle\Email\CommandMailer;

class CommandCreationListener
{
    /**
     * @var CommandMailer
     */
    private $commandMailer;

    public function __construct(CommandMailer $commandMailer)
    {
        $this->commandMailer = $commandMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On ne veut envoyer un email que pour les entités CommandMailer
        if (!$entity instanceof CommandMailer) {
            return;
        }

        $this->commandMailer->sendNewNotification($entity);
    }
}
