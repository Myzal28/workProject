<?php


namespace App\Service;

use Swift_Mailer;
class Mail
{
    public function sendMail($email,$parameters,$view,\Swift_Mailer $mailer){
        $message = (new \Swift_Message('Welcome on board !'))
            ->setFrom('planitcalendar2018@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView($view,$parameters),
                'text/html'
            );
        $mailer->send($message);
    }
}