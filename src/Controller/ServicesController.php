<?php
namespace App\Controller;

use App\Entity\AntiWasteAdvice;
use App\Entity\CookingClass;

use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\IndividualOffer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServicesController
 * @package App\Controller
 * @Route("/services/")
 *
 * Ce controller est dédié aux services mis à disposition des adhérents qui cotisent pour l'association
 */
class ServicesController extends AbstractController
{
    /**
     * @Route("waste/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="waste_advices",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function wasteAdvices(Request $request){
        // On récupère le manager Doctrine
        $manager = $this->getDoctrine()->getManager();

        // Si on ajoute un conseil on récupère le conseil
        $addAdvice = $request->get('addAdvice');
        if ($addAdvice){
            // On crée un objet conseil qu'on alimente
            $advice = new AntiWasteAdvice();
            $advice->setAdvice($addAdvice);
            $advice->setDate(new \DateTime(date('Y-m-d')));
            $advice->setUser($this->getUser());

            // On l'envoie en BDD
            $manager->persist($advice);
            $manager->flush();
        }

        $upVoteAdvice = $request->get('upvoteAdvice');
        if ($upVoteAdvice){
            $advice = $this->getDoctrine()->getRepository(AntiWasteAdvice::class)->find($upVoteAdvice);
            if ($request->getMethod() == 'POST'){
                $advice->addUpvoted($this->getUser());
            }else{
                $advice->removeUpvoted($this->getUser());
            }
            $manager->flush();
        }

        $order = $request->get('order');
        if ($order == 'date'){
            $type = 'date';
            $advices = $this->getDoctrine()->getRepository(AntiWasteAdvice::class)->findAllById();
        }else{
            $type = 'upvotes';
            $advices = $this->getDoctrine()->getRepository(AntiWasteAdvice::class)->findAllByUpvotes();
        }
        return $this->render('services/wasteAdvices.html.twig',[
            'advices' => $advices,
            'type' => $type
        ]);
    }

    /**
     * @Route("cooking/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="cooking_class",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param Request $request
     * @return Mixed
     */
    public function cookingClass(Request $request){
        $method = $request->getMethod();

        // Si la personne fait partie du service (donc donne des cours)
        if (($this->getUser()->getService() != NULL) && ($this->getUser()->getService()->getId() === 4)){
            // Si l'utilisateur a demandé tous les participants à un cours
            if ($request->get('getAll') != NULL){
                $class = $this->getDoctrine()->getRepository(CookingClass::class)->find($request->get('getAll'));
                $array = array();
                foreach ($class->getRegisteredPeople() as $people){
                    $array[] = $people;
                }
                $response = new Response(json_encode($array));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else{
                // Si il souhaite supprimer son cours
                if ($method == 'DELETE'){
                    $class = $this->getDoctrine()->getRepository(CookingClass::class)->find($request->get('cookingClass'));
                    $this->getDoctrine()->getManager()->remove($class);
                    $this->getDoctrine()->getManager()->flush();
                    $response = new Response(json_encode(array('status','deleted')));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }elseif($method == 'POST'){
                    // Si on ajoute un cours
                    $class = new CookingClass();
                    $class->setName($request->get('name'));
                    $class->setPlace($request->get('place'));
                    $class->setDuration($request->get('duration'));
                    $class->setCapacity($request->get('capacity'));
                    $class->setProfessor($this->getUser());
                    $class->setBeginning(new \DateTime($request->get('beginning_date')." ".$request->get('beginning_hour')));

                    // On persiste l'entité et on l'envoie en BDD
                    $this->getDoctrine()->getManager()->persist($class);
                    $this->getDoctrine()->getManager()->flush();

                    $classes = $this->getDoctrine()->getRepository(CookingClass::class)->findBy(array('professor' => $this->getUser()));
                    return $this->render('services/cookingClass.html.twig',[
                        "cookingClasses" => $classes,
                    ]);
                }else{
                    // Dernier scénario, si il souhaite uniquement afficher ses cours
                    $classes = $this->getDoctrine()->getRepository(CookingClass::class)->findBy(array('professor' => $this->getUser()));
                    return $this->render('services/cookingClass.html.twig',[
                        "cookingClasses" => $classes,
                    ]);
                }
            }
        }else{
            if ($method != "GET"){
                // Si la méthode est différente de GET on récupère le cours de cuisine
                $cookingClass = $this->getDoctrine()->getRepository(CookingClass::class)->find($request->get('cookingClass'));
                if ($method == "POST"){
                    // On ajoute l'utilisateur au cours de cuisine
                    if (count($cookingClass->getRegisteredPeople()) < $cookingClass->getCapacity()){
                        $cookingClass->addRegisteredPerson($this->getUser());
                        $response = new Response(json_encode(array('status' => 'added')));
                    }else{
                        $response = new Response(json_encode(array('status' => 'class_full')));
                    }
                }else{
                    // On le supprime du cours de cuisine
                    $cookingClass->removeRegisteredPerson($this->getUser());
                    $response = new Response(json_encode(array('status' => 'removed')));
                }
                // On prend en compte les modifs en BDD
                $this->getDoctrine()->getManager()->flush();

                // On envoie une réponse

                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else{
                $classes = $this->getDoctrine()->getRepository(CookingClass::class)->findAll();
                return $this->render('services/cookingClass.html.twig',[
                    "cookingClasses" => $classes
                ]);
            }
        }
    }

    /**
     * @Route("guarding/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="guarding",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function guarding(){
        return $this->render('services/guarding.html.twig');
    }

    /**
     * @Route("individual_offers/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="individual_offers",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function individualOffers(){
        $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);

        // On récupère d'abord les annonces de l'utilisateur connecté
        $userOffers = $repo->findBy(['personCreate' => $this->getUser()->getId()]);

        // On récupère ensuite toutes les annonces
        $offers = $repo->findAll();

        return $this->render('services/individualOffers.html.twig',[
            'userOffers' => $userOffers,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("api/individualOffer",
     *     defaults={"_locale"="fr"},
     *     name="individual_offers_api",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     * Micro API REST permettant de gérer les annonces PàP
     */
    public function apiIndividualOffers(Request $request)
    {
        // On récupère le repo
        $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);

        // On crée une réponse de base
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        // On récupère le manager de la BDD
        $manager = $this->getDoctrine()->getManager();

        // En fonction du type de requête
        switch ($request->getMethod()){
            // Création
            case 'POST':
                // On récupère le user en cours
                $person = $this->getUser();

                // On crée une nouvelle annonce qu'on alimente avec les champs du formulaire
                $offer = new IndividualOffer();
                $offer->setName($request->get('name'))
                    ->setPersonCreate($person)
                    ->setDescription($request->get('description'))
                    ->setDateCreate(new \Datetime)
                    ->setContact($request->get('contact'));

                // On persiste l'entité et on envoie ça en BDD
                $manager->persist($offer);
                $manager->flush();

                // On renvoie vers l'affichage des annonces
                return $this->redirectToRoute('individual_offers');
                break;
            // Suppression
            case 'DELETE':
                $offer = $repo->find($request->get('offer'));
                $manager->remove($offer);
                $manager->flush();
                $response->setStatusCode(Response::HTTP_OK);
                $response->setContent(json_encode(['status' => "deleted"]));
                break;
            // GET par défaut
            default:
                $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);
                $offer = $repo->find($request->get('offer'));
                echo json_encode($offer);
                break;
        }
        return $response;
    }
}