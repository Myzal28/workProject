<?php

namespace App\Controller;



use App\Entity\Persons;

use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\ArticlesRepository;
use App\Repository\InventoryRepository;
use App\Repository\WarehousesRepository;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
     */
    public function viewProfile(){
        return $this->render('view/profile.html.twig');
    }

    /**
     * @Route("/email/send/{email}/{name}/{_locale}",name="email_send")
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
    public function driversWork(Persons $person, CollectRepository $collectsRep){


        foreach($person->getVehicles() as $vehicule){
            $collects = $collectsRep->findBy(["vehicle"=>$vehicule]);
        }

        $serializer = new Serializer(array(new ObjectNormalizer()));
        $data = $serializer->normalize($collects, null, array('attributes' => array('id')));
        
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

}
