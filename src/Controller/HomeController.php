<?php

namespace App\Controller;


use App\Entity\Persons;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    
    /**
     * @Route("/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="home",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/contact/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="contact",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(){
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/user/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_user",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @Route("/admin/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_admin",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @Route("/client/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_client",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function viewPerson(Persons $person){

        return $this->render('view/profile.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/email/send/{email}/{name}",name="email_send")
     */
    public function mail($name, $email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Welcome on board !'))
            ->setFrom('planitcalendar2018@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )
            /*
            * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/client/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="client_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function clientsJobs(Persons $person){
        return $this->render('home/clientsJobs.html.twig');
    }
    
    /**
     * @Route("/user/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="user_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function userJobs(Persons $person){
        return $this->render('home/usersJobs.html.twig');
    }
    
}
