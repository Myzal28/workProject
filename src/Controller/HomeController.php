<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Service\Curl;
use App\Entity\Persons;
use App\Entity\Delivery;
use App\Entity\Warehouses;
use App\Form\DeliveryType;
use App\Service\QuickAlert;
use App\Form\ModifyUserType;

use App\Service\Geolocation;
use App\Repository\StatusRepository;
use Symfony\Component\Finder\Finder;

use App\Repository\CollectRepository;
use App\Repository\DeliveryRepository;
use App\Repository\InventoryRepository;
use App\Repository\WarehousesRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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
     * @Route("/delivery/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="delivery",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function delivery(){

        $delivery = new Delivery();

        $form = $this->createForm(DeliveryType::class, $delivery); 


        return $this->render('home/delivery.html.twig',[
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/contact/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="contact",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @return Response
     */
    public function contact(){
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/profile/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param Request $request
     * @return Response
     */
    public function viewProfile(Request $request){

        $user = $this->getUser();
        $options = [
            'lastname' => $user->getLastName(),
            'firstname' => $user->getFirstName(),
            'birthday' => $user->getBirthday(),
            'phoneNbr' => $user->getPhoneNbr(),
            'country' => $user->getCountry(),
            'city' => $user->getCity(),
            'zipCode' => $user->getZipcode(),
            'address' => $user->getAddress(),
        ];
        $form = $this->createForm(ModifyUserType::class,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $geoService = new Geolocation();
            $closeEnough = $geoService->closeEnough($data['address'],$data['city'],$data['zipCode'],$this->getDoctrine());
            if ($closeEnough){

                $closestWarehouse = $this->getDoctrine()->getRepository(Warehouses::class)->find($closeEnough['closestWarehouse']);

                $user->setWarehouse($closestWarehouse);
                $user->setLastName($data['lastname']);
                $user->setFirstName($data['firstname']);
                $user->setBirthday($data['birthday']);
                $user->setAddress($data['address']);
                $user->setPhoneNbr($data['phoneNbr']);
                $user->setCountry($data['country']);
                $user->setZipcode($data['zipCode']);
                $user->setCity($data['city']);
                $user->setLatitude($closeEnough['lat']);
                $user->setLongitude($closeEnough['lng']);

                $m = $this->getDoctrine()->getManager();

                $m->persist($user);
                $m->flush();
                // Modifier les informations en BDD
                $quickAlert = new QuickAlert("success",'Succès',"Vos informations ont bien été modifiées");
            }else{
                $quickAlert = new QuickAlert("error",'Erreur',"Désolé, notre service n'est pas encore disponible pour cette adresse");
            }

            return $this->render('view/profile.html.twig',[
                'form' => $form->createView(),
                'quickAlert' => $quickAlert
            ]);
        }else{
            $debug = "";
        }
        return $this->render('view/profile.html.twig',[
            'form' => $form->createView(),
        ]);
    }


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
     * @Route("/email/send/{email}/{name}/{_locale}",name="email_send")
     */
    public function mail($name, $email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Bienvenue !'))
            ->setFrom('ffw.pmv@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);

        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/client/jobs/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="client_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param CollectRepository $collectsRep
     * @return Response
     * @throws ExceptionInterface
     */
    public function clientsJobs(CollectRepository $collectsRep){
        $person = $this->getUser();
        $allCollects = $collectsRep->findBy(["personCreate"=>$person]);

        $serializer = new Serializer(array(new ObjectNormalizer()));
        $data = $serializer->normalize($allCollects, null, array('attributes' => 
          array('id','personCheck'=>array('email'),'personCreate'=>array('email'),'vehicle'=>array("id"),'status'=>array("id","status"),'commentary','dateRegister','dateCollect')));
        
        foreach($data as $i => $collect){
            $finder = new Finder();
            $finder->in($this->getParameter('kernel.root_dir').'/collects');
            $finder->name($collect["id"].".json");
            
            foreach ($finder as $file) {
                $contents = $file->getContents();
                $collectA = json_decode($contents, true);
            }
            $data[$i]["articles"]=$collectA["articles"];
        }
        
        return $this->render('home/clientsJobs.html.twig',[
            "collects"=>$data
        ]);
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

    /**
     * @Route("/internal/jobs/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="internal_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function internalJobs(Persons $person){
        return $this->render('home/internalJobs.html.twig');
    }

    /**
     * @Route("/user/driver/work/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="drivers_work",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function driversWork(Persons $person, CollectRepository $collectsRep, StatusRepository $statusRep){

        $from = new \DateTime(date("Y-m-d")." 00:00:00");
        $to = new \DateTime(date("Y-m-d")." 23:59:59");
        $status = $statusRep->find(5);

        foreach($person->getVehicles() as $vehicule){
            $qb = $collectsRep->createQueryBuilder("e");
            $qb->andWhere('e.dateCollect BETWEEN :from AND :to')
            ->andWhere('e.vehicle = :vehicle')
            ->andWhere('e.status = :status')
            ->setParameter('from', $from )
            ->setParameter('status', $status )
            ->setParameter('vehicle', $vehicule )
            ->setParameter('to', $to);

            $collects = $qb->getQuery()->getResult();
        }


        $serializer = new Serializer(array(new ObjectNormalizer()));
        $data = $serializer->normalize($collects, null, array('attributes' => array('id')));

        $collectsTemp = $serializer->normalize($collects, null, array('attributes' => array('personCreate'=>array('address','zipcode','country','firstname','lastname') )));
        
        
        if($collectsTemp != NULL){

            $i = 0;

            $waypoints = "&travelmode=driving&waypoints=";
            foreach($collectsTemp as $collect){
                $collectsJson[$i]['location'] = $collect["personCreate"]['address'].', '.$collect["personCreate"]['zipcode'].', '.$collect["personCreate"]['country'];
                $waypoints.= urlencode($collectsJson[$i]['location']."|");
                $collectsJson[$i]['fullName'] = $collect["personCreate"]['firstname'].', '.$collect["personCreate"]['lastname'];
                $i++;
            }

            $collectsJson[$i]['destination'] = $person->getWarehouse()->getAddress().', '.$person->getWarehouse()->getZipcode().', '.$person->getWarehouse()->getCountry();
            $destination = "&origin=".urlencode($collectsJson[$i]['destination'])."&destination=".urlencode($collectsJson[$i]['destination']);
            $directionsLnk = "https://www.google.com/maps/dir/?api=1".$destination.$waypoints;
            $collectsJson = json_encode($collectsJson);
        }else{
            $collectsJson = NULL;
        }
        
        $i = 0;
        
        foreach($data as $collect){
            $finder = new Finder();
            $finder->in($this->getParameter('kernel.root_dir').'/collects');
            $finder->name($collect["id"].".json");
            
                foreach ($finder as $file) {
                    $contents = $file->getContents();
                    $collectA = json_decode($contents, true);
                    // ...
                }
            
                $data[$i]["articles"]=$collectA["articles"];
            
            $i++;
        }

    
        
        return $this->render('services/driversWork.html.twig',[
            "link" => $directionsLnk,
            "jsonTab" => $collectsJson,
            "collects" => $collects,
            "data"=> $data
        ]);
    }

    /**
     * @Route("/user/stock/work/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="stock_work",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function stockWork(WarehousesRepository $warehousesRep, CollectRepository $collectsRep, StatusRepository $statusRep){
        
        $from = new \DateTime(date("Y-m-d")." 00:00:00");
        $to = new \DateTime(date("Y-m-d")." 23:59:59");
        $status = $statusRep->findBy(["statusType"=>"AST"]);
        $statusEx = $statusRep->find(15);

        $qb = $collectsRep->createQueryBuilder("e");
        $qb->andWhere('e.dateCollect BETWEEN :from AND :to')
        ->andWhere('e.status != :statusEx')
        ->setParameter('from', $from )
        ->setParameter('statusEx', $statusEx )
        ->setParameter('to', $to);

        $collects = $qb->getQuery()->getResult();

        $warehouses = $warehousesRep->findAll();

        return $this->render('services/stockWork.html.twig',[
            "status" => $status,
            "collects" => $collects,
            "warehouses" => $warehouses
        ]);
    }

    /**
     * @Route("/admin/manage/inventory/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_inventory",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageInventory(InventoryRepository $inventoriesRep){

        $inventories = $inventoriesRep->findAll();

        return $this->render('admin/manageInventory.html.twig',[
            "inventories" => $inventories
        ]);
    }

    /**
     * @Route("/new_delivery/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="new_delivery",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function newDelivery(DeliveryRepository $deliveryRep, InventoryRepository $inventoryRep){


        if(/*$delivery*/TRUE){

            $deliveryTemp = $deliveryRep->find(1);

            $stocks = $inventoryRep->findAll(["warehouse_id"=>$deliveryTemp->getWarehouse()]);

            $arrayStock = array();

            foreach($stocks as $stock){
                $foodId = $stock->getFoods()->getId();
                if( isset($arrayStock[$foodId]) ){
                    $arrayStock[$foodId]["number"] + $stock->getNumber();;
                }else{
                    $arrayStock[$foodId]["name"] =  $stock->getFoods()->getName();
                    $arrayStock[$foodId]["brands"] =  $stock->getFoods()->getBrands();
                    $arrayStock[$foodId]["code"] =  $stock->getFoods()->getCode();
                    $arrayStock[$foodId]["number"] =  $stock->getNumber();

                }
                
            }

            return $this->render('home/deliverySetup.html.twig',[
               'stock' => $arrayStock
            ]);
        }

        return $this->redirectToRoute('security_delivery_signup');;
    }
}
