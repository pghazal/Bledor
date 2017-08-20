<?php

namespace PG\PlatformBundle\Email;

use PG\PlatformBundle\Entity\Operation;

class OperationMailer
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendNewNotification(Operation $operation)
  {
    $message = new \Swift_Message(
      'Nouvelle candidature',
      'Vous avez reçu une nouvelle candidature.'
    );

    $message
      ->addTo('pg.ghazal@gmail.com')
      ->addFrom('pg.ghazal-sender@gmail.com');

    $this->mailer->send($message);
  }
}
