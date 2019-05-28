<?php

namespace App\Controller;

use App\Entity\Signup;
use App\Entity\Persons;
use App\Form\SignupPersonType;

use App\Repository\StatusRepository;
use App\Repository\PersonsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/signup/{_locale}", name="security_signup", defaults={"_locale"="fr"})
     */
    public function signup(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, PersonsRepository $persons,StatusRepository $status)
    {
        $person = new Persons();

        $form = $this->createForm(SignupPersonType::class, $person);


       
        $form->handleRequest($request);
        dump($request);
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($person, $person->getPassword());

            $person->setPassword($hash);
            $person->setDateRegister(new \Datetime);
            $person->setAdminSite(0);
            
            if($person->type_choice){
                $person->setVolunteer(1);
                $person->setInternal(0);
                $manager->persist($person);

                $manager->flush();
            }else{
                $signup = new Signup();

                $person->setVolunteer(0);
                $person->setInternal(1);

                $manager->persist($person);
                $manager->flush();

                $person = $persons->findOneBy(["email"=>$person->getEmail()]);
                $signup->setPerson($person);
                $signup->setStatus($status->find(1));
                $signup->setCommentary("En attente de vérifications !");
                $signup->setDateLastUpdate(new \Datetime);
                $manager->persist($signup);

                $manager->flush();
            }
            
            
            return $this->redirectToRoute('email_send',['name' => $person->getFirstname(), 'email'=>$person->getEmail()]);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/login/{_locale}", name="security_login", defaults={"_locale"="fr"})
    */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
    * @Route("/user/logout/{_locale}", name="security_logout", defaults={"_locale"="fr"})
    * @Route("/admin/logout/{_locale}", name="security_logout", defaults={"_locale"="fr"})
    */
    public function logout(){}

}