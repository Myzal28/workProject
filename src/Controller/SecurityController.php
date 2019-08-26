<?php

namespace App\Controller;

use App\Entity\Signup;
use App\Entity\Persons;
use App\Entity\Warehouses;

use App\Form\SignupPersonType;
use App\Service\Curl;

use App\Repository\StatusRepository;
use Datetime;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/signup/{_locale}", name="security_signup", defaults={"_locale"="fr"})
     * @param Request $request
     * @param Curl $curl
     * @param UserPasswordEncoderInterface $encoder
     * @param StatusRepository $status
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function signup(Request $request, Curl $curl, UserPasswordEncoderInterface $encoder,StatusRepository $status)
    {
        $person = new Persons();

        $form = $this->createForm(SignupPersonType::class, $person);



        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            // On formatte l'adresse de la personne pour pouvoir l'utiliser après dans l'API
            $formattedAddress = str_replace(" ","+",$person->getAddress());
            $formattedAddress .= "+".str_replace(" ", "+",$person->getCity());
            $formattedAddress .= "+".str_replace(" ","+",$person->getZipcode());

            // L'URL avec l'API Key google ressemblera à ça, on l'envoie ensuite à cURL
            $urlWithAddress = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $formattedAddress . "&key=AIzaSyDE9fld3JmAgIk2oZdBeiIf3lFxPIkCTko";

            // On vient chercher les coordonnées de la personne en cURL sur l'API Google Maps
            $geoData = $curl->getJson($urlWithAddress);


            $closeEnough = false;
            if (isset($geoData['status'])){
                switch ($geoData['status']){
                    case "OK":
                        $lat = $geoData['results'][0]['geometry']['location']['lat'];
                        $lng = $geoData['results'][0]['geometry']['location']['lng'];

                        $warehouses = $this->getDoctrine()->getRepository(Warehouses::class)->findAll();
                        foreach ($warehouses as $warehouse){
                            $urlTravelTime = "https://maps.googleapis.com/maps/api/distancematrix/json?&origins=".$lat.",".$lng."&destinations=".$warehouse->getLatitude().",".$warehouse->getLongitude()."&key=AIzaSyDE9fld3JmAgIk2oZdBeiIf3lFxPIkCTko";
                            $travelTime = $curl->getJson($urlTravelTime);
                            $total[] = $travelTime;
                            if ($travelTime['rows'][0]['elements'][0]['distance']['value'] < 3600){
                                // si a moins d'une heure de route d'un de nos entrepôts alors on l'accepte
                                $closeEnough = true;
                            }
                        }
                        break;
                    default:
                        $closeEnough = false;
                        break;
                }
            }

            if ($closeEnough){
                $hash = $encoder->encodePassword($person, $person->getPassword());

                $person->setPassword($hash);
                $person->setDateRegister(new Datetime);
                $person->setAdminSite(0);

                // On set toutes les variables à 0, celle sélectionnée sera passée à 1 après
                $person->setInternal(0);
                $person->setVolunteer(0);
                $person->setClientPro(0);
                $person->setClientPar(0);

                // On passe à 1 la variable qui nous intéresse
                switch ($person->type_choice) {
                    case 1:
                        $person->setInternal(1);

                        break;
                    case 2:
                        $person->setVolunteer(1);

                        $signup = new Signup();

                        // Dans le cas d'un bénévole il faudra qu'il soit vérifié
                        $signup->setPerson($person);
                        $signup->setStatus($status->find(1));
                        $signup->setCommentary("En attente de vérifications !");
                        $signup->setDateLastUpdate(new Datetime);

                        //$manager->persist($signup);
                        //$manager->flush();
                        break;
                    case 3:
                        $person->setClientPro(1);
                        break;
                    case 4:
                        $person->setClientPar(1);
                        break;
                }

                // On envoie toutes nos modifications en base de données
                /*
                $manager->persist($person);
                $manager->flush();
                */
                //return $this->redirectToRoute('email_send',['name' => $person->getFirstname(), 'email'=>$person->getEmail()]);
                return $this->render('security/registration.html.twig', [
                    'form' => $form->createView(),
                ]);
            }else{
                $quickAlert['icon'] = "error";
                $quickAlert['title'] = "Erreur";
                $quickAlert['text'] = "Notre service n'est malheureusement pas encore disponible dans votre ville";


                return $this->render('security/registration.html.twig', [
                    'form' => $form->createView(),
                    'quickAlert' => $quickAlert
                ]);
            }
        }



        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login/{_locale}", name="security_login", defaults={"_locale"="fr"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
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