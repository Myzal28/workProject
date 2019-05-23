<?php

namespace App\Controller;

use App\Entity\Persons;
use App\Form\SignupPersonType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/signup/{_locale}", name="security_signup", defaults={"_locale"="fr"})
     */
    public function signup(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $person = new Persons();

        $form = $this->createForm(SignupPersonType::class, $person);


       
        $form->handleRequest($request);
        dump($request);
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($person, $person->getPassword());

            $person->setPassword($hash);
            
            if($person->type_choice){
                $person->setVolunteer(1);
                $person->setInternal(0);
            }else{
                $person->setVolunteer(0);
                $person->setInternal(1);
            }

            $person->setDateRegister(new \Datetime);
            $person->setAdminSite(0);
            $manager->persist($person);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/login/{_locale}", name="security_login", defaults={"_locale"="fr"})
    */
    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
    * @Route("/user/logout/{_locale}", name="security_logout", defaults={"_locale"="fr"})
    * @Route("/admin/logout/{_locale}", name="security_logout", defaults={"_locale"="fr"})
    */
    public function logout(){}

}