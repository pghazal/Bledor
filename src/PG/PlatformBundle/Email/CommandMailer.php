<?php

namespace PG\PlatformBundle\Email;

use PG\PlatformBundle\Entity\Command;

class CommandMailer
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendNewNotification(Command $command)
  {
    $message = new \Swift_Message(
      'Nouvelle commande',
      'Vous avez reçu une nouvelle commande.'
    );

    $message
      ->addTo($command->getClient()->getEmail())
      ->addFrom('pg.ghazal-sender@gmail.com');

    $this->mailer->send($message);
  }
}
