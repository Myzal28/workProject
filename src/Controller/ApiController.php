<?php

namespace App\Controller;

use App\Entity\Foods;
use App\Entity\Collect;
use App\Entity\Persons;
use App\Entity\Status;
use App\Entity\Vehicles;
use App\Repository\FoodsRepository;
use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\PersonsRepository;
use App\Repository\CalendarRepository;
use App\Service\Curl;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/users/collect/add", name="add_articles")
     * @param PersonsRepository $personsRep
     * @param Request $request
     * @return Response
     */
    public function articlesAdd(PersonsRepository $personsRep, Request $request)
    {   
   
        $data = json_decode($request->getContent(), true);
        
        //$response->setStatusCode(200);
        /*if($person->getEmail() == null){
            $response->setStatusCode(200);
        }
        */
        return $this->render("tests/json.html.twig",[
            "table" => $data
        ]);
    }

    /**
     * @Route("api/user/connect", name="user_connect")
     * @param Request $request
     * @param PersonsRepository $personsRep
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function apiConexion(Request $request, PersonsRepository $personsRep, UserPasswordEncoderInterface $encoder)
    {

        $response = new Response();

        $user = $personsRep->findOneBy(["email"=>$request->get("_username")]);

        if($user){
            $plainPassword = $request->get('_password');

            if($encoder->isPasswordValid($user, $plainPassword)){
                $response->setContent('true');
                $response->setStatusCode(200);
                return $response;
            }
        }
        $response->setContent('false');
        $response->setStatusCode(403);
        return $response;
    }

    /**
     * @Route("/api/client/collect/create", name="create_collect")
     * @param Request $request
     * @param PersonsRepository $personsRep
     * @param ObjectManager $manager
     * @param FoodsRepository $foodsRep
     * @param StatusRepository $statusRep
     * @return Response
     * @throws \Exception
     */
    public function collectCreate(Request $request, PersonsRepository $personsRep, ObjectManager $manager, FoodsRepository $foodsRep, StatusRepository $statusRep,\Swift_Mailer $mailer)
    {
        $response = new Response();

        // On récupère le json qui nous a été envoyé
        $data = json_decode($request->getContent(), true);

        // On récupère l'utilisateur auquel est lié le JSON envoyé via son email
        $user = $personsRep->findOneBy(["email"=> $data["email"]]);

        // Si il existe
        if($user){
            /* On initialise la variable qui contiendra le poids total de son envoi et la variable qui va
             * permettre d'aller récupérer les poids
             */
            $totalWeight = 0;
            $curl = new Curl();

            // Pour chaque article envoyé par l'utilisateur
            foreach($data["articles"] as $article){

                // On récupère l'article sur l'api openfoodfact
                $foodFromApi = $curl->getFood($article['code']);

                // On incrémente le poids total de la commande
                $totalWeight += $foodFromApi['product_quantity']*$article['quantity_nbr'];

                // Si c'est la première fois qu'on rencontre ce code barre on le met en BDD
                $food = $foodsRep->findOneBy(["code"=>$article["code"]]);
                if(!($food)){
                    $food = new Foods();

                    $food->setName($article["product_name"])
                        ->setIngredients($article["ingredients_text"])
                        ->setCode($article["code"])
                        ->setQuantity($article["quantity"])
                        ->setBrands($article["brands"])
                        ->setImageUrl($article["image_url"])
                        ->setNumber(0);

                    $manager->persist($food);
                    $manager->flush();
                }
            }

            // On crée ensuite un ramassage
            $collect = new Collect();
            $status = $statusRep->findOneBy(["id"=>4]);

            $collect->setPersonCreate($user)
                    ->setStatus($status)
                    ->setCommentary("Collecte en attente de confirmation.")
                    ->setDateRegister($date = new \Datetime)
                    ->setTotalWeight($totalWeight)
                    ->setWarehouse($user->getWarehouse()->getId())
            ;
            $manager->persist($collect);
            $manager->flush();

            // On récupère toutes les commandes de l'entrepôt lié qui sont en attente
            $waitingCollects = $this->getDoctrine()->getRepository(Collect::class)->findWaiting($user->getWarehouse());

            // On va venir chercher le poids total de la commande ainsi que le temps total de l'itinéraire
            $currentTotalWeight = 0;
            $wayPointsString = "&waypoints=";

            // Pour chaque collecte en attente
            foreach ($waitingCollects as $waitingCollect){
                // On formate pour l'URL Google
                $wayPointsString .= $waitingCollect->getPersonCreate()->getLatitude().",".$waitingCollect->getPersonCreate()->getLongitude()."|";
                // On incrémente le poids total
                $currentTotalWeight += $waitingCollect->getTotalWeight();
            }

            // On formate l'URL pour l'API Google
            $origin = "origin=".$user->getWarehouse()->getLatitude().",".$user->getWarehouse()->getLongitude();
            $destination = "&destination=".$user->getWarehouse()->getLatitude().",".$user->getWarehouse()->getLongitude();
            $key = "&key=AIzaSyDE9fld3JmAgIk2oZdBeiIf3lFxPIkCTko";
            $url = "https://maps.googleapis.com/maps/api/directions/json?".$origin.$destination.$key.$wayPointsString;
            // On récupère le résultat de l'API Google
            $data = $curl->getJson($url);
            $totalDuration = 0;
            // On récupère le temps total de l'itinéraire
            foreach ($data['routes'][0]['legs'] as $step){
                $totalDuration += $step['duration']['value'];
            }

            // De base on ne lance pas la collecte, on va vérifier après si on a de quoi la lancer
            $startCollect = false;
            // On récupère un véhicule libre actuellement
            $vehicles = $this->getDoctrine()->getRepository(Vehicles::class)->findWaiting();
            $vehicle = $vehicles[0];
            // Si l'itinéraire fais plus de 6h alors on envoie la collecte a un chauffeur libre
            if ($totalDuration > 21600){
                $startCollect = true;
            }else{
                // On considère qu'on peut faire les 2/3 d'un camion en terme de poids
                $capacity = (($vehicle->getCapacity())/3)*2;

                // Si le poids total de la collecte dépasse cette capacité, alors on démarre la collecte
                if ($currentTotalWeight > $capacity){
                    $startCollect = true;
                }

            }

            if ($startCollect){
                $collectStatus = $this->getDoctrine()->getRepository(Status::class)->find(5);
                $vehicleStatus = $this->getDoctrine()->getRepository(Status::class)->find(9);
                $vehicle->setStatus($vehicleStatus);
                foreach($waitingCollects as $waitingCollect){
                    $waitingCollect->setVehicle($vehicle);
                    $waitingCollect->setStatus($collectStatus);
                    $waitingCollect->setCommentary('Collecte confirmée');
                    $waitingCollect->setDateCollect(new \DateTime('tomorrow'));
                }
                $manager->flush();
            }
            $fs = new Filesystem();
            try {
                $fs->dumpFile($this->getParameter('kernel.project_dir').'/src/collects/'.$collect->getId().'.json', $request->getContent());
            }catch(IOException $e) {
                $response->setContent($e);
                return $response;
            }
            // On va générer le pdf qui sera envoyé à l'utilisateur
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFond','arial');
            $pdf = new Dompdf();

            $html = $this->renderView('pdf/collect.html.twig',[
                'name' => 'Maxime',
                'products' => $data['articles']
            ]);

            $pdf->loadHtml($html);
            $pdf->setPaper('A4','portrait');
            $pdf->render();

            $path = $this->getParameter('kernel.project_dir') . "/emailPdfs/pdfCollect.pdf";
            file_put_contents($path ,$pdf->output());


            $message = (new \Swift_Message('Votre ramassage du ' . date('d/m/Y')))
                ->setFrom('ffw.pmv@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/addCollect.html.twig',
                        ['name' => $user->getFirstname()]
                    ),
                    'text/html'
                )
                ->attach(\Swift_Attachment::fromPath($path))
            ;
            $mailer->send($message);
            $response->setContent('true');
            $response->setStatusCode(200);
            return $response;
        }
        
        $response->setContent('false');
        $response->setStatusCode(403);
        return $response;
    }

    /**
     * @param $email
     * @param $parameters
     * @param $view
     * @param $subject
     * @param \Swift_Mailer $mailer
     */
    private function sendMail($email,$parameters,$view,$subject,\Swift_Mailer $mailer){
        $message = (new \Swift_Message($subject))
            ->setFrom('planitcalendar2018@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView($view,$parameters),
                'text/html'
            );
        $mailer->send($message);
    }


    /**
     * @Route("/test/mail")
     */

    public function testMail(\Swift_Mailer $mailer){
        $email = 'valentin77181@gmail.com';
        $parameters = ['name' => 'Valentin'];
        $view = 'email/deliveryRecap.html.twig';
        $subject = "La livraison s'est bien effectuée";

        return $this->sendMail($email, $parameters, $view, $subject, $mailer);

    }

    /**
     * @Route("/calendar/load", name="calendar_load")
     */
    public function calendarLoad(CalendarRepository $calendarRep){

        $response = new Response();

        $events = $calendarRep->findAll();
        $tab = array();
        $serializer = new Serializer(array(new ObjectNormalizer()));
        $eventsTab = $serializer->normalize($events, null, array('attributes' => array('id', 'dateStart', 'dateEnd', 'name')));
        
        foreach($eventsTab as $event){

            $eventObj = $calendarRep->find($event["id"]);
            $tab[]= array(
                "id" => $event["id"],
                "start" => $eventObj->getDateStart()->format('Y-m-d H:i:s'),
                "end" => $eventObj->getDateEnd()->format('Y-m-d H:i:s'),
                "title" => $event["name"]
            );
        }

        $response->setContent(json_encode($tab));
        $response->setStatusCode(200);
        return $response;
    }
    
    /**
     * @Route("/test")
     */
    use DataTablesTrait;
    public function showAction(Request $request)
    {
        

        $table = $this->createDataTable()
            ->add('firstName', TextColumn::class)
            ->add('lastName', TextColumn::class)
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('tests/list.html.twig', ['datatable' => $table]);
    }

    

    /**
     * @Route("/test/google", name="api")
     */
    public function apiGoogle()
    {
        return $this->render('tests/googleapi.html.twig');
    }

    /**
     * @Route("/test/calendar", name="calendar")
     */
    public function apiCalendar()
    {
        return $this->render('tests/calendar.html.twig');
    }


}
